<?php

namespace App\Http\Controllers\User\Payment;

use Instamojo\Instamojo;
use Illuminate\Http\Request;
use App\Models\User\UserPackage;
use App\Models\User\BasicSetting;
use PHPMailer\PHPMailer\Exception;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGateway;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\UserCheckoutController;

class InstamojoController extends Controller
{
    public function paymentProcess($request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {
        $data = UserPaymentGateway::whereKeyword('instamojo')->where('user_id', getUser()->id)->first();

        $paydata = $data->convertAutoData();
        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;
        Session::put('user_amount', $_amount);
        if ($paydata['sandbox_check'] == 1) {
            $api = new Instamojo($paydata['key'], $paydata['token'], 'https://test.instamojo.com/api/1.1/');
        } else {
            $api = new Instamojo($paydata['key'], $paydata['token']);
        }

        try {
            $response = $api->paymentRequestCreate(array(
                "purpose" => $_title,
                "amount" => $_amount,
                "send_email" => false,
                "email" => null,
                "redirect_url" => $notify_url
            ));
            $redirect_url = $response['longurl'];
            Session::put('user_payment_id', $response['id']);
            Session::put('user_success_url', $notify_url);
            Session::put('user_cancel_url', $cancel_url);

            return redirect($redirect_url);
        } catch (Exception $e) {
            return redirect($cancel_url)->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function successPayment(Request $request)
    {
        $user_amount = Session::get('user_amount');
        $requestData = Session::get('user_request');
        $user = getUser();
        $be = BasicSetting::where('user_id', $user->id)->firstorFail();

        $success_url = Session::get('user_success_url');
        $cancel_url = Session::get('user_cancel_url');
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_payment_id');
        $transaction_id = UserPermissionHelper::uniqidReal(8);
        $transaction_details = json_encode($request['payment_request_id']);
        $amount = $user_amount;

        $checkout = new UserCheckoutController();
        $requestData['templateType'] = 'appointment_booking_notification';
        $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be);
        $checkout->mailToTanentUser($requestData, $appointment, $amount, "Instamojo", $be, $transaction_id);
        session()->flash('success', toastrMsg('successful_payment'));
        Session::forget("user_amount");
        Session::forget('user_payment_id');
        Session::forget('user_success_url');
        Session::forget('user_cancel_url');
        $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
        return redirect($onlinesuccess);
    }

    public function cancelPayment()
    {
        session()->flash('warning', toastrMsg('cancel_payment'));
        return redirect()->route('front.user.appointment', getParam());
    }
}
