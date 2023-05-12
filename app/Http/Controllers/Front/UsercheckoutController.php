<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\User\Category;
use App\Models\User\Language;
use App\Models\User\UserTimeSlot;
use App\Http\Helpers\MegaMailer;
use App\Models\User\BasicSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\AppointmentBooking;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOfflinePaymentGateway;
use App\Http\Controllers\User\Payment\PaytmController;
use App\Http\Controllers\User\Payment\MollieController;
use App\Http\Controllers\User\Payment\PaypalController;
use App\Http\Controllers\User\Payment\StripeController;
use App\Http\Controllers\User\Payment\PaystackController;
use App\Http\Controllers\User\Payment\RazorpayController;
use App\Http\Controllers\User\Payment\InstamojoController;
use App\Http\Controllers\User\Payment\FlutterWaveController;
use App\Http\Controllers\User\Payment\MercadopagoController;
use App\Http\Controllers\User\Payment\AuthorizenetController;

class UsercheckoutController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:customer')->except('userCheckout', 'checkoutFinal', 'checkout', 'customerSuccess');
    }

    public function userCheckout(Request $request, $domain)
    {
        $user = getUser();
        $user_request = Session::get('user_request');
        $user_request['slot'] = $request->slot;
        $user_request['date'] = $request->date;
        if ($user_request['slot'] == null) {
            return redirect()->back()->with('error', __('Please select a time slot'));
        }
        $timeSlots = UserTimeSlot::where('user_id', $user->id)->where('id', $request->slotId)->first();
        $max_booking_limit  = $timeSlots->max_booking;
        $slt = ($timeSlots->start . ' - ' . $timeSlots->end);
        $countAppointment = AppointmentBooking::where('user_id', $user->id)->where('date', $request->date)->where('status', '!=', 4)->where('time', $slt)->get();
        $countAppointment = count($countAppointment);
        if (!empty($max_booking_limit)) {
            if ($max_booking_limit == $countAppointment) {
                return redirect()->back()->with('error', __('This time slot is booked! Please try another slot.'));
            }
        }
        $user_request = Session::put('user_request', $user_request);
        return redirect()->route('customer.payment', getParam());
    }
    public function checkoutFinal(Request $request, $domain)
    {
        $user = getUser();
        $user_request = Session::get('user_request');
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if (empty($user_request['category_id'])) {
            $data['total_fee'] = $ubs->appointment_price;
        } else {
            $data['total_fee'] = Category::find($user_request['category_id'])->appointment_price;
        }
        if ($ubs->full_payment == 1) {
            $data['price']  = $data['total_fee'];
        } else {
            $data['price']  = ($data['total_fee'] * $ubs->advance_percentage) / 100;
        }
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['userBs'] = $ubs;
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        $data['payment_methods'] = UserPaymentGateway::query()->where('status', 1)->where('user_id', $user->id)->get();
        
        $data['offline']  = UserOfflinePaymentGateway::where('status', 1)->where('user_id', $user->id)->get();
        $data['authuser'] = Auth::guard('customer')->user();
        $data['userCurrentLang'] = $userCurrentLang;
        return view('user-front.common.checkout', $data);
    }

    public function checkout($domain, Request $request)
    {
        // dd($request->all());
        $user_request = Session::get('user_request');
        $user  = getUser();
        $offline_payment_gateways = UserOfflinePaymentGateway::where('user_id', $user->id)->pluck('name')->toArray();
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();

        $user_request['mode'] = 'online';
        $user_request['receipt_name'] = null;
        $user_request['payment_method'] = $request->payment_method;
        Session::put('user_paymentFor', 'appointment_booking');
        Session::put('user_request', $user_request);
        $title = "You are paying for appointment";
        $description = "Congratulation you are going to book an appointment.
        Please make a payment for confirming your time slot now!";
        // dd($request->payment_method);
        if ($request->payment_method == "Paypal") {
            $amount = round($request->price == 0 ? 0 : ($request->price / $bs->base_currency_rate), 2);
            $paypal = new PaypalController();
            $cancel_url = route('customer.appointment.paypal.cancel', getParam());
            $success_url = route('customer.appointment.paypal.success', getParam());
            return $paypal->paymentProcess($user_request, $amount, $title, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Stripe") {
            $amount = round(($request->price / $bs->base_currency_rate), 2);
            $stripe = new StripeController();
            $user_request['cardNumber'] = $request->cardNumber;
            $user_request['month'] = $request->month;
            $user_request['year'] = $request->year;
            $user_request['cardCVC'] = $request->cardCVC;
            $cancel_url = route('customer.appointment.stripe.cancel', getParam());
            return $stripe->paymentProcess($user_request, $amount, $title, NULL, $cancel_url);
        } elseif ($request->payment_method == "Paytm") {
            if ($bs->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_paytm_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('paytm-') . time();
            $callback_url = route('customer.appointment.paytm.status', getParam());
            $paytm = new PaytmController();
            return $paytm->paymentProcess($user_request, $amount, $item_number, $callback_url);
        } elseif ($request->payment_method == "Paystack") {
            if ($bs->base_currency_text != "NGN") {
                return redirect()->back()->with('error', __('only_paystack_NGN'))->withInput($request->all());
            }
            $amount = $request->price * 100;
            $email = $user_request['email'];
            $success_url = route('customer.appointment.paystack.success', getParam());
            $payStack = new PaystackController();
            return $payStack->paymentProcess($user_request, $amount, $email, $success_url, $bs);
        } elseif ($request->payment_method == "Razorpay") {
            if ($bs->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_razorpay_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('razorpay-') . time();
            $cancel_url = route('customer.appointment.razorpay.cancel', getParam());
            $success_url = route('customer.appointment.razorpay.success', getParam());
            $razorpay = new RazorpayController();
            return $razorpay->paymentProcess($user_request, $amount, $item_number, $cancel_url, $success_url, $title, $description, $bs);
        } elseif ($request->payment_method == "Instamojo") {
            if ($bs->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_instamojo_INR'))->withInput($request->all());
            }
            if ($request->price < 9) {
                return redirect()->back()->with('error', 'Minimum 10 INR required for this payment gateway');
            }
            $amount = $request->price;
            $success_url = route('customer.appointment.instamojo.success', getParam());
            $cancel_url = route('customer.appointment.instamojo.cancel', getParam());
            $instaMojo = new InstamojoController();
            return $instaMojo->paymentProcess($user_request, $amount, $success_url, $cancel_url, $title, $bs);
        } elseif ($request->payment_method == "Mercadopago") {
            if ($bs->base_currency_text != "BRL") {
                return redirect()->back()->with('error', __('only_mercadopago_BRL'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $user_request['email'];
            $success_url = route('customer.appointment.mercadopago.success', getParam());
            $cancel_url = route('customer.appointment.mercadopago.cancel', getParam());
            $mercadopagoPayment = new MercadopagoController();
            return $mercadopagoPayment->paymentProcess($user_request, $amount, $success_url, $cancel_url, $email, $title, $description, $bs);
        } elseif ($request->payment_method == "Flutterwave") {
            $available_currency = array(
                'BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'
            );
            if (!in_array($bs->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $user_request['email'];
            $item_number = uniqid('flutterwave-') . time();
            $cancel_url = route('customer.appointment.flutterwave.cancel', getParam());
            $success_url = route('customer.appointment.flutterwave.success', getParam());
            $flutterWave = new FlutterWaveController();
            return $flutterWave->paymentProcess($user_request, $amount, $email, $item_number, $success_url, $cancel_url, $bs);
        } elseif ($request->payment_method == "Authorize.net") {
            $available_currency = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            if (!in_array($bs->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $cancel_url = route('customer.appointment.anet.cancel', getParam());
            $anetPayment = new AuthorizenetController();
            return $anetPayment->paymentProcess($user_request, $amount, $cancel_url, $title, $bs);
        } elseif ($request->payment_method == "Mollie") {
            $available_currency = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');
            if (!in_array($bs->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $success_url = route('customer.appointment.mollie.success', getParam());
            $cancel_url = route('customer.appointment.mollie.cancel', getParam());
            $molliePayment = new MollieController();
            return $molliePayment->paymentProcess($user_request, $amount, $success_url, $cancel_url, $title, $bs);
        } elseif (in_array($request->payment_method, $offline_payment_gateways)) {
            $user_request['mode'] = 'offline';
            $user_request['status'] = 0;
            $user_request['receipt_name'] = null;
            Session::put('user_request', $user_request);
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
            $appointment = $this->store($user_request, $transaction_id, $transaction_details, $amount, $bs);
            $user_request['templateType'] = 'appointment_booking_notification';
            $this->mailToTanentUser($user_request, $appointment, $amount, $request->payment_method, $bs, $transaction_id);
            $success_message  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($success_message);
        }
    }

    public function store($request, $transaction_id, $transaction_details, $amount, $be)
    {
        $user = getUser();
        $customer = Auth::guard('customer')->user();
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        // Serial code
        $sl = BasicSetting::select(DB::raw("serial_reset , CONCAT( '', LPAD(serial_reset + 1,5,'0') ) as slId"))->where('user_id', $user->id)
            ->first();
        if ($sl) {
            $sl_no = $sl->slId;
        } else {
            $sl_no = '00001';
        }
        // Serial code 

        if (empty($request['category_id'])) {
            $total_fee = $ubs->appointment_price;
        } else {
            $total_fee = Category::find($request['category_id'])->appointment_price;
        }

        if ($ubs->full_payment == 1) {
            $payment_status = 2;
            $due  = null;
        } else {
            $due  = $total_fee - (($total_fee * $ubs->advance_percentage) / 100);
            $payment_status = 3;
        }

        $appointment  =  AppointmentBooking::create([
            'customer_id' => $customer ? $customer->id : null,
            'user_id' => $user->id,
            'name' => $request['name'],
            'email' => $request['email'],
            'date' => $request['date'],
            'category_id' => $request['category_id'] ?? null,
            'amount' => $amount,
            'total_amount' => $total_fee,
            'due_amount' => $due,
            'time' => $request['slot'],
            'serial_number' => $sl_no,
            'transaction_id' => $transaction_id,
            'transaction_details' => $transaction_details,
            'status' => 1,
            'payment_status' => $payment_status,
            'receipt' => $request['receipt_name'],
            'payment_method' => $request['payment_method'],
            'currency' => $be->base_currency_text,
            'details' => json_encode($request['customer_form']),
        ]);
        Session::forget('user_request');
        $ubs->serial_reset = $sl->serial_reset + 1;
        $ubs->save();
        return $appointment;
    }
    public function mailToTanentUser($requestData, $appointment, $amount, $method, $be, $transaction_id)
    {
        $user = Auth::guard('customer')->user();
        $category = Category::find($appointment->category_id)->name ?? '';
        $file_name = $this->userMakeInvoice($requestData, $user, $appointment, $category, $amount, $method, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id);
        $mailer = new MegaMailer();
        $data = [
            'toMail' => $requestData['email'],
            'toName' => $requestData['name'],
            'serial_number' => $appointment->serial_number,
            'date' => $appointment->date,
            'slot' => $appointment->time,
            'amount' => $amount,
            'due_amount' => $appointment->due_amount,
            'total_amount' => $appointment->total_amount,
            'customer_name' => $requestData['name'],
            'category' => $category ?? '-',
            'user_appointment' => $file_name,
            'website_title' => $be->website_title,
            'templateType' => $requestData['templateType'],
            'user' => Auth::guard('web')->check() ? Auth::guard('web')->user() : getUser()
        ];
        $mailer->mailFromTanent($data);
    }
    public function customerSuccess($domain, AppointmentBooking $appointment)
    {
        $data['appointment'] = $appointment;
        return view('user-front.online-success', $data);
    }
    public function offlineSuccess($domain, AppointmentBooking $appointment)
    {
        $data['appointment'] = $appointment;
        return view('user-front.offline-success', $data);
    }
}
