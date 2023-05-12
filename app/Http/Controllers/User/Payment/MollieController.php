<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\UserCheckoutController;

class MollieController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGateway::whereKeyword('mollie')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        Config::set('mollie.key', $paydata['key']);
    }

    public function paymentProcess($request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {

        $notify_url = $_success_url;
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $bex->base_currency_text,
                'value' => '' . sprintf('%0.2f', $_amount) . '', // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => $_title,
            'redirectUrl' => $notify_url,
        ]);

        /** add payment ID to session **/
        Session::put('user_amount', $_amount);
        Session::put('user_payment_id', $payment->id);
        Session::put('user_success_url', $_success_url);

        $payment = Mollie::api()->payments()->get($payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user_amount = Session::get('user_amount');
        $user  = getUser();
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        // $requestData['user_id'] = Auth::guard('customer')->user()->id ?? getUser()->id;
        $cancel_url = Session::get('cancel_url');
        $payment_id = Session::get('user_payment_id');
        /** Get the payment ID before session clear **/
        $payment = Mollie::api()->payments()->get($payment_id);
        if ($payment->status == 'paid') {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($payment);
            $amount = $user_amount;
            $checkout = new UserCheckoutController();
            $requestData['templateType'] = 'appointment_booking_notification';
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Paypal", $bs, $transaction_id);
            session()->flash('success', toastrMsg('successful_payment'));

            Session::forget('user_amount');
            Session::forget('user_payment_id');
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        }
        return redirect($cancel_url);
    }

    public function cancelPayment()
    {
        session()->flash('warning', toastrMsg('cancel_payment'));
        return redirect()->route('front.user.appointment', getParam());
    }
}
