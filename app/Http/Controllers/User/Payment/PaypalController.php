<?php

namespace App\Http\Controllers\User\Payment;

use Redirect;
use Carbon\Carbon;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use App\Http\Helpers\MegaMailer;
use App\Models\User\UserPackage;
use PayPal\Api\PaymentExecution;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\UsercheckoutController;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $data = UserPaymentGateway::whereKeyword('paypal')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $paypal_conf = Config::get('paypal');
        $paypal_conf['client_id'] = $paydata['client_id'];
        $paypal_conf['secret'] = $paydata['client_secret'];
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function paymentProcess($request, $_amount, $_title, $_success_url, $_cancel_url)
    {

        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;
        $success_url = $_success_url;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($title)
            /** item name **/
            ->setCurrency("USD")
            ->setQuantity(1)
            ->setPrice($price);
        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($title . ' Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($success_url)
            /** Specify return URL **/
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {

            return redirect()->back()->with('error', $ex->getMessage());
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        Session::put('user_amount', $_amount);
        Session::put('user_paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        return redirect()->back()->with('error', 'Unknown error occurred');
    }

    public function successPayment(Request $request)
    {
        $user_amount = Session::get('user_amount');
        $requestData = Session::get('user_request');
        $user = getUser();
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_paypal_payment_id');
        /** clear the session payment ID **/
        $cancel_url = route('customer.appointment.paypal.cancel', getParam());
        if (empty($request['PayerID']) || empty($request['token'])) {
            return redirect($cancel_url);
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = $payment;
            $amount = $user_amount;
            $checkout = new UsercheckoutController();
            $requestData['templateType'] = 'appointment_booking_notification';
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Paypal", $bs, $transaction_id);
            session()->flash('success', toastrMsg('successful_payment'));
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        }
        return redirect($cancel_url);
    }

    public function cancelPayment()
    {
        session()->flash('warning', toastrMsg('cancel_payment'));
        return redirect()->route('front.user.appointment', getParam());
    }
}
