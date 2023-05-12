<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserOfflinePaymentGateway;

class GatewayController extends Controller
{
    public function index()
    {
        $data['paypal'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paypal']])->first();
        $data['stripe'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'stripe']])->first();
        $data['paystack'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paystack']])->first();
        $data['paytm'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytm']])->first();
        $data['flutterwave'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'flutterwave']])->first();
        $data['instamojo'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'instamojo']])->first();
        $data['mollie'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mollie']])->first();
        $data['razorpay'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'razorpay']])->first();
        $data['mercadopago'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mercadopago']])->first();
        $data['anet'] = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'authorize.net']])->first();
        // dd($data);
        return view('user.gateways.index', $data);
    }

    public function paypalUpdate(Request $request)
    {
        $paypal = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paypal']])->first();
        $paypal->status = $request->status;

        $information = [];
        $information['client_id'] = $request->client_id;
        $information['client_secret'] = $request->client_secret;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your PayPal account.";

        $paypal->information = json_encode($information);

        $paypal->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function stripeUpdate(Request $request)
    {
        $stripe = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'stripe']])->first();
        $stripe->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Credit account.";

        $stripe->information = json_encode($information);

        $stripe->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function paystackUpdate(Request $request)
    {
        $paystack = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paystack']])->first();
        $paystack->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['email'] = $request->email;
        $information['text'] = "Pay via your Paystack account.";

        $paystack->information = json_encode($information);

        $paystack->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function paytmUpdate(Request $request)
    {
        $paytm = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytm']])->first();
        $paytm->status = $request->status;

        $information = [];
        $information['environment'] = $request->environment;
        $information['merchant'] = $request->merchant;
        $information['secret'] = $request->secret;
        $information['website'] = $request->website;
        $information['industry'] = $request->industry;
        $information['text'] = "Pay via your paytm account.";

        $paytm->information = json_encode($information);

        $paytm->save();


        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function flutterwaveUpdate(Request $request)
    {
        $flutterwave = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'flutterwave']])->first();
        $flutterwave->status = $request->status;

        $information = [];
        $information['public_key'] = $request->public_key;
        $information['secret_key'] = $request->secret_key;
        $information['text'] = "Pay via your Flutterwave account.";

        $flutterwave->information = json_encode($information);

        $flutterwave->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function instamojoUpdate(Request $request)
    {
        $instamojo = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'instamojo']])->first();
        $instamojo->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Instamojo account.";

        $instamojo->information = json_encode($information);

        $instamojo->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function mollieUpdate(Request $request)
    {
        $mollie = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mollie']])->first();
        $mollie->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['text'] = "Pay via your Mollie Payment account.";

        $mollie->information = json_encode($information);

        $mollie->save();

        $arr = ['MOLLIE_KEY' => $request->key];
        setEnvironmentValue($arr);
        \Artisan::call('config:clear');

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function razorpayUpdate(Request $request)
    {
        $razorpay = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'razorpay']])->first();
        $razorpay->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Razorpay account.";

        $razorpay->information = json_encode($information);

        $razorpay->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function anetUpdate(Request $request)
    {
        $anet = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'authorize.net']])->first();
        $anet->status = $request->status;

        $information = [];
        $information['login_id'] = $request->login_id;
        $information['transaction_key'] = $request->transaction_key;
        $information['public_key'] = $request->public_key;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Authorize.net account.";

        $anet->information = json_encode($information);

        $anet->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function mercadopagoUpdate(Request $request)
    {
        $mercadopago = UserPaymentGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mercadopago']])->first();
        $mercadopago->status = $request->status;

        $information = [];
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Mercado Pago account.";

        $mercadopago->information = json_encode($information);

        $mercadopago->save();

        $request->session()->flash('success', toastrMsg("Updated_successfully!"));

        return back();
    }

    public function offline(Request $request)
    {
        $data['ogateways'] = UserOfflinePaymentGateway::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'DESC')->get();
        return view('user.gateways.offline.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
            'is_receipt' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        $in['user_id'] = Auth::guard('web')->user()->id;
        UserOfflinePaymentGateway::create($in);
        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->except('_token', 'ogateway_id');
        UserOfflinePaymentGateway::where('id', $request->ogateway_id)->update($in);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function status(Request $request)
    {
        $og = UserOfflinePaymentGateway::find($request->ogateway_id);
        $og->status = $request->status;
        $og->save();
        Session::flash('success', toastrMsg('Status_Changed_successfully!'));
        return back();
    }

    public function delete(Request $request)
    {
        $ogateway = UserOfflinePaymentGateway::findOrFail($request->ogateway_id);
        $ogateway->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
}
