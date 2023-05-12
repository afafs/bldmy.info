<?php

namespace App\Http\Controllers\User\Payment;


use Illuminate\Http\Request;
use App\Models\User\UserPackage;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Front\UserCheckoutController;

class PaytmController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGateway::whereKeyword('paytm')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();

        Config::set('services.paytm-wallet.env', $paydata['environment']);
        Config::set('services.paytm-wallet.merchant_id', $paydata['merchant']);
        Config::set('services.paytm-wallet.merchant_key', $paydata['secret']);
        Config::set('services.paytm-wallet.merchant_website', $paydata['website']);
        Config::set('services.paytm-wallet.industry_type', $paydata['industry']);
    }

    public function paymentProcess($request, $_amount, $_item_number, $_callback_url)
    {
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => time(),
            'user' => Auth::guard('customer')->user()->id ?? getUser()->id,
            'mobile_number' => Auth::guard('customer')->user()->contact_number ?? '0000000000',
            'email' => $request['email'],
            'amount' => round($_amount, 2),
            'callback_url' => $_callback_url
        ]);
        Session::put('user_amount', $_amount);
        return $payment->receive();
    }

    public function handlePaytmRequest($_item_number, $amount, $callback_url)
    {
        $data = UserPaymentGateway::whereKeyword('paytm')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();

        // Load all functions of encdec_paytm.php and config-paytm.php
        $this->getAllEncdecFunc();
        // $this->getConfigPaytmSettings();
        $checkSum = "";
        $paramList = array();
        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = $paydata['merchant'];
        $paramList["ORDER_ID"] = $_item_number;
        $paramList["CUST_ID"] = $_item_number;
        $paramList["INDUSTRY_TYPE_ID"] = $paydata['industry'];
        $paramList["CHANNEL_ID"] = 'WEB';
        $paramList["TXN_AMOUNT"] = $amount;
        $paramList["WEBSITE"] = $paydata['website'];
        $paramList["CALLBACK_URL"] = $callback_url;

        $paytm_merchant_key = $paydata['secret'];
        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, $paytm_merchant_key);
        return array(
            'checkSum' => $checkSum,
            'paramList' => $paramList
        );
    }

    function getAllEncdecFunc()
    {
        function encrypt_e($input, $ky)
        {
            $key = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
            return $data;
        }

        function decrypt_e($crypt, $ky)
        {
            $key = html_entity_decode($ky);
            $iv = "@@@@&&&&####$$$$";
            $data = openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
            return $data;
        }

        function pkcs5_pad_e($text, $blocksize)
        {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }

        function pkcs5_unpad_e($text)
        {
            $pad = ord($text[strlen($text) - 1]);
            if ($pad > strlen($text))
                return false;
            return substr($text, 0, -1 * $pad);
        }

        function generateSalt_e($length)
        {
            $random = "";
            srand((float)microtime() * 1000000);
            $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
            $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
            $data .= "0FGH45OP89";
            for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
            }
            return $random;
        }

        function checkString_e($value)
        {
            if ($value == 'null')
                $value = '';
            return $value;
        }

        function getChecksumFromArray($arrayList, $key, $sort = 1)
        {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getChecksumFromString($str, $key)
        {
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function verifychecksum_e($arrayList, $key, $checksumvalue)
        {
            $arrayList = removeCheckSumParam($arrayList);
            ksort($arrayList);
            $str = getArray2StrForVerify($arrayList);
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function verifychecksum_eFromStr($str, $key, $checksumvalue)
        {
            $paytm_hash = decrypt_e($checksumvalue, $key);
            $salt = substr($paytm_hash, -4);
            $finalString = $str . "|" . $salt;
            $website_hash = hash("sha256", $finalString);
            $website_hash .= $salt;
            $validFlag = "FALSE";
            if ($website_hash == $paytm_hash) {
                $validFlag = "TRUE";
            } else {
                $validFlag = "FALSE";
            }
            return $validFlag;
        }

        function getArray2Str($arrayList)
        {
            $findme = 'REFUND';
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pos = strpos($value, $findme);
                $pospipe = strpos($value, $findmepipe);
                if ($pos !== false || $pospipe !== false) {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function getArray2StrForVerify($arrayList)
        {
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function redirect2PG($paramList, $key)
        {
            $hashString = getchecksumFromArray($paramList, $key);
            $checksum = encrypt_e($hashString, $key);
        }

        function removeCheckSumParam($arrayList)
        {
            if (isset($arrayList["CHECKSUMHASH"])) {
                unset($arrayList["CHECKSUMHASH"]);
            }
            return $arrayList;
        }

        function getTxnStatus($requestParamList)
        {
            return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
        }

        function getTxnStatusNew($requestParamList)
        {
            return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
        }

        function initiateTxnRefund($requestParamList)
        {
            $CHECKSUM = getRefundChecksumFromArray($requestParamList, PAYTM_MERCHANT_KEY, 0);
            $requestParamList["CHECKSUM"] = $CHECKSUM;
            return callAPI(PAYTM_REFUND_URL, $requestParamList);
        }

        function callAPI($apiURL, $requestParamList)
        {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData = json_encode($requestParamList);
            $postData = 'JsonData=' . urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData)
                )
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse, true);
            return $responseParamList;
        }

        function callNewAPI($apiURL, $requestParamList)
        {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData = json_encode($requestParamList);
            $postData = 'JsonData=' . urlencode($JsonData);
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($postData)
                )
            );
            $jsonResponse = curl_exec($ch);
            $responseParamList = json_decode($jsonResponse, true);
            return $responseParamList;
        }

        function getRefundChecksumFromArray($arrayList, $key, $sort = 1)
        {
            if ($sort != 0) {
                ksort($arrayList);
            }
            $str = getRefundArray2Str($arrayList);
            $salt = generateSalt_e(4);
            $finalString = $str . "|" . $salt;
            $hash = hash("sha256", $finalString);
            $hashString = $hash . $salt;
            $checksum = encrypt_e($hashString, $key);
            return $checksum;
        }

        function getRefundArray2Str($arrayList)
        {
            $findmepipe = '|';
            $paramStr = "";
            $flag = 1;
            foreach ($arrayList as $key => $value) {
                $pospipe = strpos($value, $findmepipe);
                if ($pospipe !== false) {
                    continue;
                }
                if ($flag) {
                    $paramStr .= checkString_e($value);
                    $flag = 0;
                } else {
                    $paramStr .= "|" . checkString_e($value);
                }
            }
            return $paramStr;
        }

        function callRefundAPI($refundApiURL, $requestParamList)
        {
            $jsonResponse = "";
            $responseParamList = array();
            $JsonData = json_encode($requestParamList);
            $postData = 'JsonData=' . urlencode($JsonData);
            $ch = curl_init($refundApiURL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $refundApiURL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $jsonResponse = curl_exec($ch);
            return json_decode($jsonResponse, true);
        }
    }

    public function paymentStatus(Request $request)
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        $requestData = Session::get('user_request');
        $user_amount = Session::get('user_amount');

        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id
        $transaction->getTransactionId(); // Get transaction id


        if ($transaction->isSuccessful()) {
            $user = getUser();
            $be = BasicSetting::where('user_id', $user->id)->firstorFail();
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request);
            $amount = $user_amount;
            $checkout = new UserCheckoutController();
            $requestData['templateType'] = 'appointment_booking_notification';
            $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be);
            $appointment = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be);
            $checkout->mailToTanentUser($requestData, $appointment, $amount, "Paytm", $be, $transaction_id);
            session()->flash('success', toastrMsg('successful_payment'));
            Session::forget('user_amount');
            $onlinesuccess  = route('customer.success.page', [getParam(), $appointment->id]);
            return redirect($onlinesuccess);
        } else {
            session()->flash('warning', toastrMsg('cancel_payment'));
            return redirect()->route('front.user.appointment', getParam());
        }
    }
}
