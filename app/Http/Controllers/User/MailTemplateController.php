<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserEmailTemplate;

class MailTemplateController extends Controller
{
    public function mailTemplates()
    {

        $data['templates'] = UserEmailTemplate::where('user_id', Auth::guard('web')->user()->id)->get();

        return view('user.settings.email.templates', $data);
    }

    public function editMailTemplate($id)
    {
        $templateInfo = UserEmailTemplate::findOrFail($id);
        return view('user.settings.email.edit-template', compact('templateInfo'));
    }

    public function updateMailTemplate(Request $request, $id)
    {

        $rules = [
            'email_subject' => 'required',
            'email_body' => 'required'
        ];
      
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
           
            return redirect()->back()->withErrors($validator->errors());
        }
      
        UserEmailTemplate::findOrFail($id)->update([
            'email_body' => clean($request->email_body),
            'email_subject' => $request->email_subject,
        ]);

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return redirect()->back();
    }
}
