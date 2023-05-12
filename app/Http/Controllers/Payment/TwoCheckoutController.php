<?php

namespace App\Http\Controllers\Payment;

require_once("core/app/Http/Helpers/Twocheckout/Twocheckout.php");
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twocheckout;
use Twocheckout_Charge;
use Twocheckout_Error;


class TwoCheckoutController extends Controller
{
    public function __construct()
    {
        Twocheckout::privateKey('F2D8C47F-E97E-4FF6-B66F-739FE109FF1D'); 
        Twocheckout::sellerId('251369017333'); 

    }

    public function index()
    {
        return view('twocheckout');
    }

    public function charge(Request $request) {
        try {
            $charge = Twocheckout_Charge::auth(array(
                "merchantOrderId" => "123",
                "token"      => $request->token,
                "currency"   => 'USD',
                "total"      => '10.00',
                "billingAddr" => array(
                    "name" => 'Testing Tester',
                    "addrLine1" => '123 Test St',
                    "city" => 'Columbus',
                    "state" => 'OH',
                    "zipCode" => '43123',
                    "country" => 'USA',
                    "email" => 'example@2co.com',
                    // "phoneNumber" => '555-555-5555'
                ),
                "demo" => true
            ));
        

            if ($charge['response']['responseCode'] == 'APPROVED') {
                echo "Thanks for your Order!";
                echo "<h3>Return Parameters:</h3>";
                echo "<pre>";
                print_r($charge);
                echo "</pre>";
        
            }
        } catch (Twocheckout_Error $e) {
            print_r($e->getMessage());
        }
        
    }
}
