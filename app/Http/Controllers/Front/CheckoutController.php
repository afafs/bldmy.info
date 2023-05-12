<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Language;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\OfflineGateway;
use App\Http\Helpers\MegaMailer;
use App\Models\User\BasicSetting;
use App\Models\User\HomePageText;
use App\Models\User\UserDay as Day;
use App\Models\User\UserPermission;
use App\Http\Controllers\Controller;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Http\Controllers\Payment\PaytmController;
use App\Http\Controllers\Payment\MollieController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\FlutterWaveController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\AuthorizenetController;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {

        $coupon = Coupon::where('code', Session::get('coupon'))->first();
        if (!empty($coupon)) {
            $coupon_count = $coupon->total_uses;
            if ($coupon->maximum_uses_limit != 999999) {
                if ($coupon_count == $coupon->maximum_uses_limit) {
                    Session::forget('coupon');
                    session()->flash('warning', __('This coupon reached maximum limit'));
                    return redirect()->back();
                }
            }
        }

        $offline_payment_gateways = OfflineGateway::all()->pluck('name')->toArray();
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $request['status'] = 1;
        $request['mode'] = 'online';
        $request['receipt_name'] = null;
        Session::put('paymentFor', 'membership');
        $title = "You are purchasing a membership";
        $description = "Congratulation you are going to join our membership.Please make a payment for confirming your membership now!";
        if ($request->package_type == "trial") {
            $package = Package::find($request['package_id']);
            $request['price'] = 0.00;
            $request['payment_method'] = "-";
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "Trial";
            $user = $this->store($request->all(), $transaction_id, $transaction_details, $request->price, $be, $request->password);



            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
            $activation = Carbon::parse($lastMemb->start_date);
            $expire = Carbon::parse($lastMemb->expire_date);
            $file_name = $this->makeInvoice($request->all(), "membership", $user, $request->password, $request['price'], "Trial", $request['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

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
                'templateType' => 'registration_with_trial_package',
                'type' => 'registrationWithTrialPackage'
            ];
            $mailer->mailFromAdmin($data);

            session()->flash('success', __('successful_payment'));
            return redirect()->route('membership.trial.success');
        } elseif ($request->price == 0) {
            $package = Package::find($request['package_id']);
            $request['price'] = 0.00;
            $request['payment_method'] = "-";
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "Free";
            $user = $this->store($request->all(), $transaction_id, $transaction_details, $request->price, $be, $request->password);


            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
            $activation = Carbon::parse($lastMemb->start_date);
            $expire = Carbon::parse($lastMemb->expire_date);
            $file_name = $this->makeInvoice($request->all(), "membership", $user, $request->password, $request['price'], "Free", $request['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

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
                'templateType' => 'registration_with_free_package',
                'type' => 'registrationWithFreePackage'
            ];
            $mailer->mailFromAdmin($data);


            session()->flash('success', __('successful_payment'));
            return redirect()->route('success.page');
        } elseif ($request->payment_method == "Paypal") {
            $amount = round(($request->price / $be->base_currency_rate), 2);
            $paypal = new PaypalController();
            $cancel_url = route('membership.paypal.cancel');
            $success_url = route('membership.paypal.success');
            return $paypal->paymentProcess($request, $amount, $title, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Stripe") {
            $amount = round(($request->price / $be->base_currency_rate), 2);
            $stripe = new StripeController();
            $cancel_url = route('membership.stripe.cancel');
            return $stripe->paymentProcess($request, $amount, $title, NULL, $cancel_url);
        } elseif ($request->payment_method == "Paytm") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_paytm_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('paytm-') . time();
            $callback_url = route('membership.paytm.status');
            $paytm = new PaytmController();
            return $paytm->paymentProcess($request, $amount, $item_number, $callback_url);
        } elseif ($request->payment_method == "Paystack") {
            if ($be->base_currency_text != "NGN") {
                return redirect()->back()->with('error', __('only_paystack_NGN'))->withInput($request->all());
            }
            $amount = $request->price * 100;
            $email = $request->email;
            $success_url = route('membership.paystack.success');
            $payStack = new PaystackController();
            return $payStack->paymentProcess($request, $amount, $email, $success_url, $be);
        } elseif ($request->payment_method == "Razorpay") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_razorpay_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('razorpay-') . time();
            $cancel_url = route('membership.razorpay.cancel');
            $success_url = route('membership.razorpay.success');
            $razorpay = new RazorpayController();
            return $razorpay->paymentProcess($request, $amount, $item_number, $cancel_url, $success_url, $title, $description, $bs, $be);
        } elseif ($request->payment_method == "Instamojo") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_instamojo_INR'))->withInput($request->all());
            }
            if ($request->price < 9) {
                session()->flash('warning', 'Minimum 10 INR required for this payment gateway');
                return back()->withInput($request->all());
            }
            $amount = $request->price;
            $success_url = route('membership.instamojo.success');
            $cancel_url = route('membership.instamojo.cancel');
            $instaMojo = new InstamojoController();
            return $instaMojo->paymentProcess($request, $amount, $success_url, $cancel_url, $title, $be);
        } elseif ($request->payment_method == "Mercado Pago") {
            if ($be->base_currency_text != "BRL" && $be->base_currency_text != "ARS") {
                return redirect()->back()->with('error', __('only_mercadopago_BRL'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $request->email;
            $success_url = route('membership.mercadopago.success');
            $cancel_url = route('membership.mercadopago.cancel');
            $mercadopagoPayment = new MercadopagoController();
            return $mercadopagoPayment->paymentProcess($request, $amount, $success_url, $cancel_url, $email, $title, $description, $be);
        } elseif ($request->payment_method == "Flutterwave") {
            $available_currency = array(
                'BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'
            );
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $request->email;
            $item_number = uniqid('flutterwave-') . time();
            $cancel_url = route('membership.flutterwave.cancel');
            $success_url = route('membership.flutterwave.success');
            $flutterWave = new FlutterWaveController();
            return $flutterWave->paymentProcess($request, $amount, $email, $item_number, $success_url, $cancel_url, $be);
        } elseif ($request->payment_method == "Authorize.net") {
            $available_currency = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $cancel_url = route('membership.anet.cancel');
            $anetPayment = new AuthorizenetController();
            return $anetPayment->paymentProcess($request, $amount, $cancel_url, $title, $be);
        } elseif ($request->payment_method == "Mollie Payment") {
            $available_currency = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = round(($request->price / $be->base_currency_rate), 2);
            $success_url = route('membership.mollie.success');
            $cancel_url = route('membership.mollie.cancel');
            $molliePayment = new MollieController();
            return $molliePayment->paymentProcess($request, $amount, $success_url, $cancel_url, $title, $be);
        } elseif (in_array($request->payment_method, $offline_payment_gateways)) {
            $request['mode'] = 'offline';
            $request['status'] = 0;
            $request['receipt_name'] = null;
            if ($request->has('receipt')) {
                $filename = time() . '.' . $request->file('receipt')->getClientOriginalExtension();
                $directory = public_path("assets/front/img/membership/receipt");
                if (!file_exists($directory)) mkdir($directory, 0775, true);
                $request->file('receipt')->move($directory, $filename);
                $request['receipt_name'] = $filename;
            }
            $amount = $request->price;
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "offline";
            $password = $request->password;
            $this->store($request, $transaction_id, json_encode($transaction_details), $amount, $be, $password);
            return redirect()->route('membership.offline.success');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $transaction_id, $transaction_details, $amount, $be, $password)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $bs = $currentLang->basic_setting;
        $token = md5(time() . $request['username'] . $request['email']);
        $verification_link = "<a href='" . url('register/mode/' . $request['mode'] . '/verify/' . $token) . "'>" . "<button type=\"button\" class=\"btn btn-primary\">Click Here</button>" . "</a>";
        $user = User::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'username' => $request['username'],
                'password' => bcrypt($password),
                'status' => $request["status"],
                'address' => $request["address"] ? $request["address"] : null,
                'city' => $request["city"] ? $request["city"] : null,
                'state' => $request["district"] ? $request["district"] : null,
                'country' => $request["country"] ? $request["country"] : null,
                'verification_link' => $token,
            ]);
            $deLang = User\Language::firstOrFail();
            $langCount = User\Language::where('user_id', $user->id)->where('is_default', 1)->count();
            if ($langCount == 0) {
                $language =  User\Language::create([
                    'name' => $deLang->name,
                    'code' => $deLang->code,
                    'is_default' => 1,
                    'rtl' => $deLang->rtl,
                    'user_id' => $user->id,
                    'keywords' => $deLang->keywords
                ]);
                HomePageText::create([
                    'user_id' => $user->id,
                    'language_id' => $language->id
                ]);
            }
            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->first_name,
                'customer_name' => $user->first_name,
                'verification_link' => $verification_link,
                'website_title' => $bs->website_title,
                'templateType' => 'email_verification',
                'type' => 'emailVerification'
            ];
            $package = Package::findOrFail($request['package_id']);
            $mailer->mailFromAdmin($data);
            Membership::create([
                'package_price' => $package->price,
                'discount' => session()->has('coupon_amount') ? session()->get('coupon_amount') : 0,
                'coupon_code' => session()->has('coupon') ? session()->get('coupon') : NULL,
                'price' => $amount,
                'currency' => $be->base_currency_text ? $be->base_currency_text : "USD",
                'currency_symbol' => $be->base_currency_symbol ? $be->base_currency_symbol : $be->base_currency_text,
                'payment_method' => $request["payment_method"],
                'transaction_id' => $transaction_id ? $transaction_id : 0,
                'status' => $request["status"] ? $request["status"] : 0,
                'is_trial' => $request["package_type"] == "regular" ? 0 : 1,
                'trial_days' => $request["package_type"] == "regular" ? 0 : $request["trial_days"],
                'receipt' => $request["receipt_name"] ? $request["receipt_name"] : null,
                'transaction_details' => $transaction_details ? $transaction_details : null,
                'settings' => json_encode($be),
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'start_date' => Carbon::parse($request['start_date']),
                'expire_date' => Carbon::parse($request['expire_date']),
            ]);
            $package = Package::findOrFail($request['package_id']);
            $features = json_decode($package->features, true);
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            UserPermission::create([
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'permissions' => json_encode($features)
            ]);
            BasicSetting::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'website_title' => $user->username,
                'from_name' => $user->username,
            ]);
           
            // create Day table 
            $days = [
                'Sunday' => 0,
                'Monday' => 1,
                'Tuesday' => 2,
                'Wednesday' => 3,
                'Thursday' => 4,
                'Friday' => 5,
                'Saturday' => 6
            ];
            foreach ($days as $key => $index) {
                Day::create([
                    'user_id' => $user->id,
                    'day' => $key,
                    'index' => $index,
                ]);
            }
            // create payment gateways 
            $payment_keywords = ['flutterwave', 'razorpay', 'paytm', 'paystack', 'instamojo', 'stripe', 'paypal', 'mollie', 'mercadopago', 'authorize.net'];
            foreach ($payment_keywords as $key => $value) {
                UserPaymentGateway::create([
                    'title' => null,
                    'user_id' => $user->id,
                    'details' => null,
                    'keyword' => $value,
                    'subtitle' => null,
                    'name' => ucfirst($value),
                    'type' => 'automatic',
                    'information' => null
                ]);
            }
            // create email template 
            $templates = ['email_verification', 'appointment_booking_notification', 'reset_password'];
            foreach ($templates as $key => $val) {
                UserEmailTemplate::create([
                    'user_id' => $user->id,
                    'email_type' => $val,
                    'email_subject' => str_replace('_', ' ', $val),
                    'email_body' => '<p></p>',
                ]);
            }
        } else {
            $user = $user->first();
        }
        // coupon update  
        if (Session::has('coupon')) {
            $coupon = Coupon::where('code', Session::get('coupon'))->first();
            $coupon->total_uses = $coupon->total_uses + 1;
            $coupon->save();
        }
        return $user;
    }

    public function onlineSuccess()
    {
        Session::forget('coupon');
        Session::forget('coupon_amount');
        return view('front.success');
    }

    public function offlineSuccess()
    {
        Session::forget('coupon');
        Session::forget('coupon_amount');
        return view('front.offline-success');
    }

    public function trialSuccess()
    {
        Session::forget('coupon');
        Session::forget('coupon_amount');
        return view('front.trial-success');
    }

    public function coupon(Request $request)
    {
        if (session()->has('coupon')) {
            return 'Coupon already applied';
        }
        $coupon = Coupon::where('code', $request->coupon)->first();
        if (empty($coupon)) {
            return 'This coupon does not exist';
        }
        $coupon_count = $coupon->total_uses;
        if ($coupon->maximum_uses_limit != 999999) {
            if ($coupon_count >= $coupon->maximum_uses_limit) {
                return 'This coupon reached maximum limit';
            }
        }
        $start = Carbon::parse($coupon->start_date);
        $end = Carbon::parse($coupon->end_date);
        $today = Carbon::parse(Carbon::now()->format('m/d/Y'));
        $packages = $coupon->packages;
        $packages = json_decode($packages, true);
        $packages = !empty($packages) ? $packages : [];
        if (!in_array($request->package_id, $packages)) {
            return 'This coupon is not valid for this package';
        }

        if ($today->greaterThanOrEqualTo($start) && $today->lessThanOrEqualTo($end)) {
            $package = Package::find($request->package_id);
            $price = $package->price;
            if ($coupon->type == 'percentage') {
                $cAmount = ($price * $coupon->value) / 100;
            } else {
                $cAmount = $coupon->value;
            }
            Session::put('coupon', $request->coupon);
            Session::put('coupon_amount', round($cAmount, 2));
            return "success";
        } else {
            return 'This coupon does not exist';
        }
    }
}
