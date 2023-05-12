<?php

namespace App\Http\Controllers\User\Payment;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Models\User\UserPackage;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\UserCheckoutController;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGateway::whereKeyword('razorpay')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess($request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs)
    {
        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;
        $orderData = [
            'receipt' => $_title,
            'amount' => $_amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        Session::put('user_order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;
        Session::put('user_amount', $_amount);
        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key" => $this->keyId,
            "amount" => $_amount,
            "name" => $_title,
            "description" => $_description,
            "prefill" => [
                "name" => $request->name ?? null,
                "email" => $request->address ?? null,
                "contact" => $request->razorpay_phone ?? null,
            ],
            "notes" => [
                "address" => $request->razorpay_address ?? null,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bs->base_currency_text !== 'INR') {
            $data['display_currency'] = $bs->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $bs->base_currency_text;

        return view('user-front.razorpay', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function successPayment(Request $request)
    {
        $user_amount = Session::get('user_amount');
        $requestData = Session::get('user_request');
        $user = getUser();
        $be = BasicSetting::where('user_id', $user->id)->firstorFail();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_order_payment_id');
        $success = true;
        if (empty($request['razorpay_payment_id']) === false) {
            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $request['razorpay_payment_id'],
                    'razorpay_signature' => $request['razorpay_signature']
                );
                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                dd($e);
                $success = false;
            }
        }
        if ($success === true) {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request);
            $amount = $user_amount;
            $checkout = new UsercheckoutController();
            $requestData['templateType'] = 'appointment_booking_notification';
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Razorpay", $be, $transaction_id);
            session()->flash('success', toastrMsg('successful_payment'));
            Session::forget('user_amount');
            Session::forget('user_order_payment_id');
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        }
        return redirect()->route('front.user.appointment', getParam());
    }

    public function cancelPayment()
    {
        session()->flash('warning', toastrMsg('cancel_payment'));
        return redirect()->route('front.user.appointment', getParam());
    }
}
