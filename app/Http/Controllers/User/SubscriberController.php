<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\User\Subscriber;
use App\Models\BasicSetting;
use App\Models\BasicExtended;
use App\Mail\ContactMail;
use Session;
use Mail;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $data['subscs'] = Subscriber::where('user_id', Auth::guard('web')->user()->id)
            ->when($term, function ($query, $term) {
                return $query->where('email', 'LIKE', '%' . $term . '%');
            })->orderBy('id', 'DESC')->paginate(10);
        return view('user.subscribers.index', $data);
    }

    public function store(Request $request, $domain)
    {
        $user = getUser();
        $request->validate([
            'email' => ['required',
                function ($attribute, $value, $fail) use ($user) {
                    $subscriber = Subscriber::where([
                        ['email', $value],
                        ['user_id', $user->id]
                    ])->get();
                    if ($subscriber->count() > 0) {
                        Session::flash('error', 'You already subscribed this user');
                        $fail(':attribute already subscribed for this user');
                    }
                },
            ],
        ]);
        $request['user_id'] = $user->id;
        Subscriber::create($request->all());
        Session::flash('success', toastrMsg('You_subscribed_successfully!'));
        return back();
    }

    public function mailsubscriber()
    {
        return view('user.subscribers.mail');
    }

    public function getMailInformation()
    {
        $data['info'] = \App\Models\User\BasicSetting::where('user_id', Auth::guard('web')->user()->id)->select('email', 'from_name')->first();
        return view('user.subscribers.mail-information', $data);
    }

    public function storeMailInformation(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'from_name' => 'required'
        ], [
            'email.required' => 'The email field is required',
            'from_name.required' => 'The from name field is required'
        ]);
        $info = \App\Models\User\BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $info->email = $request->email;
        $info->from_name = $request->from_name;
        $info->save();
        Session::flash('success', toastrMsg('Store_successfully!'));
        return back();
    }

    public function subscsendmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $sub = $request->subject;
        $msg = $request->message;

        $subscs = Subscriber::where('user_id', Auth::guard('web')->user()->id)->get();
        $info = \App\Models\User\BasicSetting::where('user_id', Auth::guard('web')->user()->id)->select('email', 'from_name')->first();
        $email = $info->email ?? Auth::guard('web')->user()->email;
        $name = $info->from_name ?? Auth::guard('web')->user()->username;
        $settings = BasicSetting::first();
        $from = $settings->contact_mail;

        $be = BasicExtended::first();

        $mail = new PHPMailer(true);

        if ($be->is_smtp == 1) {
            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host = $be->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = $be->smtp_username;                     // SMTP username
                $mail->Password = $be->smtp_password;                               // SMTP password
                $mail->SMTPSecure = $be->encryption;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = $be->smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->addReplyTo($email);

                //Recipients
                $mail->setFrom($be->from_mail, $name);

                foreach ($subscs as $key => $subsc) {
                    $mail->addAddress($subsc->email);     // Add a recipient
                }
            } catch (Exception $e) {

            }
        } else {
            try {
                //Recipients
                $mail->setFrom($be->from_mail, $name);
                $mail->addReplyTo($email);
                foreach ($subscs as $key => $subsc) {
                    $mail->addAddress($subsc->email);  // Add a recipient
                }
            } catch (Exception $e) {

            }
        }
        // Content
        $mail->isHTML(true);   // Set email format to HTML
        $mail->Subject = $sub;
        $mail->Body = $msg;

        $mail->send();

        Session::flash('success', toastrMsg('Mail_sent_successfully!'));
        return back();
    }


    public function delete(Request $request)
    {
        Subscriber::findOrFail($request->subscriber_id)->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            Subscriber::findOrFail($id)->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }
}
