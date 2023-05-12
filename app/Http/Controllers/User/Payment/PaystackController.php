<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\UserCheckoutController;

class PaystackController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess($request, $_amount, $_email, $_success_url, $bex)
    {
        $data = UserPaymentGateway::whereKeyword('paystack')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $secret_key = $paydata['key'];
        Session::put('user_amount', $_amount);
        $curl = curl_init();
        $callback_url = $_success_url; // url to go to after payment

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => $_amount,
                'email' => $_email,
                'callback_url' => $callback_url
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . $secret_key, //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            return redirect()->back()->with('error', $err);
        }
        $tranx = json_decode($response, true);

        if (!$tranx['status']) {
            return redirect()->back()->with("error", $tranx['message']);
        }
        return redirect($tranx['data']['authorization_url']);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user_amount = Session::get('user_amount');
        $user = getUser();
        $be = BasicSetting::where('user_id', $user->id)->firstorFail();
        if ($request['trxref'] === $request['reference']) {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request['trxref']);
            $amount = $user_amount;

            $checkout = new UserCheckoutController();
            $requestData['templateType'] = 'appointment_booking_notification';
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Paystack", $be, $transaction_id);
            Session::forget('user_amount');
            session()->flash('success', toastrMsg('successful_payment'));
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        } else {
            session()->flash('warning', toastrMsg('cancel_payment'));
            return redirect()->route('front.user.appointment', getParam());
        }
    }
}
