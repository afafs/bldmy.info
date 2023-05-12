<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Models\BasicSetting;
use App\Models\Membership;
use App\Models\Package;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AuthorizenetController extends Controller
{
    public $gateway;
  
    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('authorize.net')->first();
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
        Session::put('request', $request->all());
        if ($request->input('opaqueDataDescriptor') && $request->input('opaqueDataValue')) {
     
            try {
                // Generate a unique merchant site transaction ID.
                $transactionId = rand(100000000, 999999999);
                $response = $this->gateway->authorize([
                    'amount' => $_amount,
                    'currency' => $be->base_currency_text,
                    'transactionId' => $transactionId,
                    'opaqueDataDescriptor' => $request->input('opaqueDataDescriptor'),
                    'opaqueDataValue' => $request->input('opaqueDataValue'),
                ])->send();
         
                if($response->isSuccessful()) {
         
                    // Captured from the authorization response.
                    $transactionReference = $response->getTransactionReference();
         
                    $response = $this->gateway->capture([
                        'amount' => $_amount,
                        'currency' => $be->base_currency_text,
                        'transactionReference' => $transactionReference,
                    ])->send();
                    
         
                    $transaction_id = $response->getTransactionReference();
     
                    // Insert transaction data into the database
                    $isPaymentExist = Membership::where('transaction_id', $transaction_id)->first();
     
                    if(!$isPaymentExist)
                    {
                        $paymentFor = Session::get('paymentFor');
                        $package = Package::find($request['package_id']);
                        $transaction_id = $transaction_id ;
                        $transaction_details = NULL;
                        if ($paymentFor == "membership") {
                            $amount = $request['price'];
                            $password = $request['password'];
                            $checkout = new CheckoutController();
                            $user = $checkout->store($request, $transaction_id, $transaction_details, $amount,$be,$password);

                            

                            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                            $activation = Carbon::parse($lastMemb->start_date);
                            $expire = Carbon::parse($lastMemb->expire_date);
                            $file_name = $this->makeInvoice($request,"membership",$user,$password,$amount,"Authorize.net",$request['phone'],$be->base_currency_symbol_position,$be->base_currency_symbol,$be->base_currency_text,$transaction_id,$package->title);
                            $bs = BasicSetting::select('website_title')->first();

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
                        }
                        elseif($paymentFor == "extend") {
                            $amount = $request['price'];
                            $password = uniqid('qrcode');
                            $checkout = new UserCheckoutController();
                            $user = $checkout->store($request, $transaction_id, $transaction_details, $amount,$be,$password);
                            

                            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                            $activation = Carbon::parse($lastMemb->start_date);
                            $expire = Carbon::parse($lastMemb->expire_date);
                            $file_name = $this->makeInvoice($request,"extend",$user,$password,$amount,$request["payment_method"],$user->phone_number,$be->base_currency_symbol_position,$be->base_currency_symbol,$be->base_currency_text,$transaction_id,$package->title);
                            $bs = BasicSetting::select('website_title')->first();

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
     
                } else {
                    // not successful
                    $request->session()->flash('error', $response->getMessage());
                    return redirect($_cancel_url);
                }
            } catch(\Exception $e) {
                $request->session()->flash('error', $e->getMessage());
                return redirect($_cancel_url);
            }
        }
    }

    public function cancelPayment()
    {
        $request = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        if($paymentFor == "membership"){
            return redirect()->route('front.register.view',['status' => $request['package_type'],'id' => $request['package_id']])->withInput($request);
        }else{
            return redirect()->route('user.plan.extend.checkout',['package_id' => $request['package_id']])->withInput($request);
        }
    }
}  
