<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\User\UserCustomDomain;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use Validator;

class CustomDomainController extends Controller
{
    public function texts() {
        $data['abe'] = BasicExtended::select('domain_request_success_message', 'cname_record_section_title', 'cname_record_section_text')->first();
        return view('admin.domains.custom-texts', $data);
    }

    public function updateTexts(Request $request) {
        $rules = [
            'success_message' => 'required|max:255',
            'cname_record_section_title' => 'required|max:255',
            'cname_record_section_text' => 'required'
        ];
        $request->validate($rules);

        $be = BasicExtended::first();
        $be->domain_request_success_message = clean($request->success_message);
        $be->cname_record_section_title = $request->cname_record_section_title;
        $be->cname_record_section_text = clean($request->cname_record_section_text);
        $be->save();

        $request->session()->flash('success', __('Updated successfully'));
        return back();
    }

    public function index(Request $request) {
        $rcDomains = UserCustomDomain::orderBy('id', 'DESC')
        ->when($request->domain, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                        $query->where('current_domain', 'LIKE', '%' . $request->domain . '%')
                        ->orWhere('requested_domain', 'LIKE', '%' . $request->domain . '%');
            });
        })
        ->when($request->username, function ($query) use ($request) {
            return $query->whereHas('user', function($query) use ($request) {
                    $query->where('username',$request->username);
                });
        });
        if (empty($request->type)) {
            $rcDomains = $rcDomains->paginate(10);
        } elseif ($request->type == 'pending') {
            $rcDomains = $rcDomains->where('status', 0)->paginate(10);
        } elseif ($request->type == 'connected') {
            $rcDomains = $rcDomains->where('status', 1)->paginate(10);
        } elseif ($request->type == 'rejected') {
            $rcDomains = $rcDomains->where('status', 2)->paginate(10);
        } else {
            return view('errors.404');
        }
        $data['rcDomains'] = $rcDomains;
        return view('admin.domains.custom', $data);
    }

    public function status(Request $request) {
        $rcDomain = UserCustomDomain::findOrFail($request->domain_id);
        $rcDomain->status = $request->status;
        $rcDomain->save();

        // if the requested domain is connected
        if ($request->status == 1) {
            if (!empty($rcDomain->user)) {
                $user = $rcDomain->user;

                $bs = BasicSetting::firstOrFail();
                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'requested_domain' => $rcDomain->requested_domain,
                    'previous_domain' => !empty($rcDomain->current_domain) ? $rcDomain->current_domain : 'Not Available',
                    'website_title' => $bs->website_title,
                    'templateType' => 'custom_domain_connected',
                    'type' => 'customDomainConnected'
                ];
                $mailer->mailFromAdmin($data);
            }
        } elseif ($request->status == 2) {
            if (!empty($rcDomain->user)) {
                $user = $rcDomain->user;
                $currDomCount = $user->custom_domains()->where('status', 1)->count();
                if ($currDomCount > 0) {
                    $currDom = $user->custom_domains()->where('status', 1)->orderBy('id', 'DESC')->first()->requested_domain;
                }

                $bs = BasicSetting::firstOrFail();
                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'requested_domain' => $rcDomain->requested_domain,
                    'current_domain' => !empty($currDom) ? $currDom : 'Not Available',
                    'website_title' => $bs->website_title,
                    'templateType' => 'custom_domain_rejected',
                    'type' => 'customDomainRejected'
                ];
                $mailer->mailFromAdmin($data);
            }
        }

        $request->session()->flash('success', __('Updated successfully'));
        return back();
    }


    public function mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $be = BasicExtended::first();
        $from = $be->from_mail;

        $sub = $request->subject;
        $msg = $request->message;
        $to = $request->email;

        // Send Mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();
            } catch (\Exception $e) {

            }
        } else {
            try {

                //Recipients
                $mail->setFrom($from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();
            } catch (\Exception $e) {

            }
        }

        Session::flash('success', __('Mail sent successfully!'));
        return "success";
    }

    public function delete(Request $request)
    {
        $domainId = $request->domain_id;
        $cdomain = UserCustomDomain::findOrFail($domainId);
        $cdomain->delete();
        $request->session()->flash('success', __('Deleted successfully!'));
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $cdomain = UserCustomDomain::findOrFail($id);
            $cdomain->delete();
        }
        $request->session()->flash('success', __('Bulk deleted successfully!'));
        return "success";
    }
}
