<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PDF;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendMailWithPhpMailer($request, $file_name, $be, $subject, $body, $email, $name)
    {
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host = $be->smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $be->smtp_username;
                $mail->Password = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port = $be->smtp_port;
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);
                if ($file_name) {
                    $mail->addAttachment(public_path('assets/front/invoices/' . $file_name));
                }


                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->send();
                if ($file_name) {
                    @unlink(public_path('assets/front/invoices/' . $file_name));
                }
            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        } else {
            try {
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);
                if ($file_name) {
                    $mail->addAttachment(public_path('assets/front/invoices/' . $file_name));
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->send();
                if ($file_name) {
                    @unlink(public_path('assets/front/invoices/' . $file_name));
                }
            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        }
    }

    public function makeInvoice($request, $key, $member, $password, $amount, $payment_method, $phone, $base_currency_symbol_position, $base_currency_symbol, $base_currency_text, $order_id, $package_title)
    {
        $file_name = uniqid($key) . ".pdf";
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.membership', compact('request', 'member', 'password', 'amount', 'payment_method', 'phone', 'base_currency_symbol_position', 'base_currency_symbol', 'base_currency_text', 'order_id', 'package_title'));
        $output = $pdf->output();
        @mkdir(public_path('assets/front/invoices/'), 0775, true);
        file_put_contents(public_path('assets/front/invoices/' . $file_name), $output);
        return $file_name;
    }

    public function resetPasswordMail($email, $name, $subject, $body)
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;

        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host = $be->smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $be->smtp_username;
                $mail->Password = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port = $be->smtp_port;
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->send();
            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        } else {
            try {
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->send();
            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        }
    }

    public function getUserCurrentLanguage($userId)
    {
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $userId)->firstOrFail();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->firstOrFail();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->firstOrFail();
        }
        return $userCurrentLang;
    }


    // tanent user invoice 
    public function userMakeInvoice($request, $member, $appointment, $category, $amount, $payment_method, $base_currency_symbol_position, $base_currency_symbol, $base_currency_text, $order_id)
    {
        $file_name = uniqid(8) . ".pdf";
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.user_appointment', compact('request', 'member', 'appointment','category', 'amount', 'payment_method', 'base_currency_symbol_position', 'base_currency_symbol', 'base_currency_text', 'order_id'));
        $output = $pdf->output();
        @mkdir(public_path('assets/front/invoices/'), 0775, true);
        file_put_contents(public_path('assets/front/invoices/' . $file_name), $output);
        return $file_name;
    }
}
