<?php

namespace App\Http\Controllers\User\Payment;

use Carbon\Carbon;
use Omnipay\Omnipay;
use App\Models\Package;
use App\Models\BasicSetting;
use Illuminate\Http\Request;
use App\Http\Helpers\MegaMailer;
use App\Models\User\UserPackage;
use App\Models\User\UserMembership;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\UserCheckoutController;

class AuthorizenetController extends Controller
{
    public $gateway;
    public function __construct()
    {
        $data = UserPaymentGateway::whereKeyword('authorize.net')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName($paydata['login_id']);
        $this->gateway->setTransactionKey($paydata['transaction_key']);
        if ($paydata['sandbox_check'] == 1) {
            $this->gateway->setTestMode(true);
        }
    }

    public function paymentProcess(Request $request, $_amount, $_cancel_url, $_title, $be)
    {
        if ($request->opaqueDataDescriptor && $request->opaqueDataValue) {
            Session::put('user_request', $request->all());
            // Generate a unique merchant site transaction ID.
            $transactionId = rand(100000000, 999999999);
            $response = $this->gateway->authorize([
                'amount' => $_amount,
                'currency' => $be->base_currency_text,
                'transactionId' => $transactionId,
                'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
                'opaqueDataValue' => $request->opaqueDataValue,
            ])->send();

            $transactionReference = $response->getTransactionReference();
            $response = $this->gateway->capture([
                'amount' => $_amount,
                'currency' => $be->base_currency_text,
                'transactionReference' => $transactionReference,
            ])->send();
            $transaction_id = $response->getTransactionReference();
            $requestData = Session::get('user_request');
            $user = getUser();
            $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
            // Insert transaction data into the database
            $transaction_id = $transaction_id;
            $transaction_details = NULL;
            $amount = $request['price'];
            $checkout = new UserCheckoutController();
            $request['templateType'] = 'appointment_booking_notification';
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Paypal", $bs, $transaction_id);
            session()->flash('success', toastrMsg('successful_payment'));
            Session::forget('user_amount');
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        }
    }

    public function cancelPayment()
    {
        session()->flash('warning', toastrMsg('cancel_payment'));
        return redirect()->route('front.user.appointment', getParam());
    }
}
