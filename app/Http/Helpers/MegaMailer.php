<?php

namespace App\Http\Helpers;

use App\Models\Language;
use App\Models\BasicExtended;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\User\BasicSetting;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\User\UserEmailTemplate;
use Illuminate\Support\Facades\Session;

class MegaMailer
{

    public function mailFromAdmin($data)
    {
        $temp = EmailTemplate::where('email_type', '=', $data['templateType'])->first();

        $body = $temp->email_body;
        if (array_key_exists('username', $data)) {
            $body = preg_replace("/{username}/", $data['username'], $body);
        }
        if (array_key_exists('replaced_package', $data)) {
            $body = preg_replace("/{replaced_package}/", $data['replaced_package'], $body);
        }
        if (array_key_exists('removed_package_title', $data)) {
            $body = preg_replace("/{removed_package_title}/", $data['removed_package_title'], $body);
        }
        if (array_key_exists('package_title', $data)) {
            $body = preg_replace("/{package_title}/", $data['package_title'], $body);
        }
        if (array_key_exists('package_price', $data)) {
            $body = preg_replace("/{package_price}/", $data['package_price'], $body);
        }
        if (array_key_exists('activation_date', $data)) {
            $body = preg_replace("/{activation_date}/", $data['activation_date'], $body);
        }
        if (array_key_exists('expire_date', $data)) {
            $body = preg_replace("/{expire_date}/", $data['expire_date'], $body);
        }
        if (array_key_exists('requested_domain', $data)) {
            $body = preg_replace("/{requested_domain}/", "<a href='http://" . $data['requested_domain'] . "'>" . $data['requested_domain'] . "</a>", $body);
        }
        if (array_key_exists('previous_domain', $data)) {
            $body = preg_replace("/{previous_domain}/", "<a href='http://" . $data['previous_domain'] . "'>" . $data['previous_domain'] . "</a>", $body);
        }
        if (array_key_exists('current_domain', $data)) {
            $body = preg_replace("/{current_domain}/", "<a href='http://" . $data['current_domain'] . "'>" . $data['current_domain'] . "</a>", $body);
        }
        if (array_key_exists('subdomain', $data)) {
            $body = preg_replace("/{subdomain}/", "<a href='http://" . $data['subdomain'] . "'>" . $data['subdomain'] . "</a>", $body);
        }
        if (array_key_exists('last_day_of_membership', $data)) {
            $body = preg_replace("/{last_day_of_membership}/", $data['last_day_of_membership'], $body);
        }
        if (array_key_exists('login_link', $data)) {
            $body = preg_replace("/{login_link}/", $data['login_link'], $body);
        }
        if (array_key_exists('customer_name', $data)) {
            $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
        }
        if (array_key_exists('verification_link', $data)) {
            $body = preg_replace("/{verification_link}/", $data['verification_link'], $body);
        }
        if (array_key_exists('website_title', $data)) {
            $body = preg_replace("/{website_title}/", $data['website_title'], $body);
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;
            } catch (Exception $e) {
            }
        }
        try {
            //Recipients
            $mail->setFrom($be->from_mail, $be->from_name);
            $mail->addAddress($data['toMail'], $data['toName']);
            // Attachments
            if (array_key_exists('membership_invoice', $data)) {
                $mail->addAttachment(public_path('assets/front/invoices/' . $data['membership_invoice']));
            }
            // Content
            $mail->isHTML(true);
            $mail->Subject = $temp->email_subject;
            $mail->Body    = $body;
            $mail->send();
            // Attachments
            if (array_key_exists('membership_invoice', $data)) {
                @unlink(public_path('assets/front/invoices/' . $data['membership_invoice']));
            }
        } catch (Exception $e) {
        }
    }

    public function mailToAdmin($data)
    {
        $be = BasicExtended::first();
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
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        }
        try {
            $mail->setFrom($data['fromMail'], $data['fromName']);
            $mail->addAddress($be->from_mail);     // Add a recipient

            // Attachments
            if (array_key_exists('attachments', $data)) {
                $mail->addAttachment(public_path('front/invoices/' . $data['attachments'])); // Add attachments
            }

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = $data['subject'];
            $mail->Body = $data['body'];
            $mail->addReplyTo($data['fromMail']);  // reply to
            $mail->send();
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
    }
    public function mailContactMessage($data)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }

        try {
            //Recipients
            $mail->setFrom($be->from_mail, $be->from_name);
            $mail->addAddress($data['toMail'], $data['toName']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = $data['body'];
            $mail->send();
        } catch (Exception $e) {
            Session::flash('error', $e);
            return back();
        }
    }

    public function mailFromTanent($data)
    {
        $temp = UserEmailTemplate::where('email_type', '=', $data['templateType'])->where('user_id', $data['user']->id)->first();
        $body = $temp->email_body;

        if (array_key_exists('customer_name', $data)) {
            $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
        }
        if (array_key_exists('date', $data)) {
            $body = preg_replace("/{booking_date}/", $data['date'], $body);
        }
        if (array_key_exists('serial_number', $data)) {
            $body = preg_replace("/{sl_no}/", $data['serial_number'], $body);
        }
        if (array_key_exists('category', $data)) {
            $body = preg_replace("/{category}/", $data['category'], $body);
        }
        if (array_key_exists('slot', $data)) {
            $body = preg_replace("/{booking_time}/", $data['slot'], $body);
        }
        if (array_key_exists('total_amount', $data)) {
            $body = preg_replace("/{total_fee}/", $data['total_amount'], $body);
        }
        if (array_key_exists('amount', $data)) {
            $body = preg_replace("/{paid}/", $data['amount'], $body);
        }
        if (array_key_exists('due_amount', $data)) {
            $body = preg_replace("/{due}/", $data['due_amount'], $body);
        }
        if (array_key_exists('website_title', $data)) {
            $body = preg_replace("/{website_title}/", $data['website_title'], $body);
        }
        $be = BasicExtended::firstorFail();
        $userBe = BasicSetting::where('user_id', $data['user']->id)->select('from_name', 'email')->firstorFail();
     
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
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
                return back();
            }
        }
        try {
            //Recipients
            $mail->setFrom($be->from_mail, $userBe->from_name ?? User::findOrFail($data['user']->id)->username);
            $mail->addAddress($data['toMail'], $data['toName']);
            $mail->addReplyTo($userBe->email ?? User::findOrFail($data['user']->id)->email);

            // Attachments
            if (array_key_exists('user_appointment', $data)) {
                $mail->addAttachment(public_path('assets/front/invoices/' . $data['user_appointment']));
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $temp->email_subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return back();
        }
    }
}
