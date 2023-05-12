<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Models\Language;
use App\Models\Package;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('razorpay')->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs, $bex)
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
      
        Session::put('request', $request->all());
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;

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
                "name" => $request->name,
                "email" => $request->address,
                "contact" => $request->razorpay_phone,
            ],
            "notes" => [
                "address" => $request->razorpay_address,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bex->base_currency_text !== 'INR') {
            $data['display_currency'] = $bex->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $bex->base_currency_text;

        return view('front.razorpay', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $bs = $currentLang->basic_setting;
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('order_payment_id');
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
                $success = false;
            }
        }

        if ($success === true) {
            $package = Package::find($requestData['package_id']);
            $paymentFor = Session::get('paymentFor');
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request);
            if ($paymentFor == "membership") {
                $amount = $requestData['price'];
                $password = $requestData['password'];
                $checkout = new CheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = $this->makeInvoice($requestData, "membership", $user, $password, $amount, "Razorpay", $requestData['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'package_title' => $package->title,
                    'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                    'activation_date' => $activation->toFormattedDateString(),
                    'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                    'membership_invoice' => $file_name,
                    'website_title' => $bs->website_title,
                    'templateType' => 'registration_with_premium_package',
                    'type' => 'registrationWithPremiumPackage'
                ];
                $mailer->mailFromAdmin($data);

                session()->flash('success', __('successful_payment'));
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            } elseif ($paymentFor == "extend") {
                $amount = $requestData['price'];
                $password = uniqid('qrcode');
                $checkout = new UserCheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);


                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = $this->makeInvoice($requestData, "extend", $user, $password, $amount, $requestData["payment_method"], $user->phone_number, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'package_title' => $package->title,
                    'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                    'activation_date' => $activation->toFormattedDateString(),
                    'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                    'membership_invoice' => $file_name,
                    'website_title' => $bs->website_title,
                    'templateType' => 'membership_extend',
                    'type' => 'membershipExtend'
                ];
                $mailer->mailFromAdmin($data);

                session()->flash('success', __('successful_payment'));
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            }
        }
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('user.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('user.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
