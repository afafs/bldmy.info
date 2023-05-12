<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class MollieController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
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
        Session::put('request', $request->all());
        Session::put('payment_id', $payment->id);
        Session::put('success_url', $_success_url);

        $payment = Mollie::api()->payments()->get($payment->id);

        return redirect($payment->getCheckoutUrl(), 303);

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
        $cancel_url = Session::get('cancel_url');
        $payment_id = Session::get('payment_id');
        /** Get the payment ID before session clear **/

        $payment = Mollie::api()->payments()->get($payment_id);

        if ($payment->status == 'paid') {
            $paymentFor = Session::get('paymentFor');
            $package = Package::find($requestData['package_id']);
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($payment);
            if ($paymentFor == "membership") {
                $amount = $requestData['price'];
                $password = $requestData['password'];
                $checkout = new CheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount,$be,$password);


                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = $this->makeInvoice($requestData,"membership",$user,$password,$amount,"Mollie",$requestData['phone'],$be->base_currency_symbol_position,$be->base_currency_symbol,$be->base_currency_text,$transaction_id,$package->title);

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
                $amount = $requestData['price'];
                $password = uniqid('qrcode');
                $checkout = new UserCheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount,$be,$password);


                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = $this->makeInvoice($requestData,"extend",$user,$password,$amount,$requestData["payment_method"],$user->phone_number,$be->base_currency_symbol_position,$be->base_currency_symbol,$be->base_currency_text,$transaction_id,$package->title);

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
        return redirect($cancel_url);
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if($paymentFor == "membership"){
            return redirect()->route('front.register.view',['status' => $requestData['package_type'],'id' => $requestData['package_id']])->withInput($requestData);
        }else{
            return redirect()->route('user.plan.extend.checkout',['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
