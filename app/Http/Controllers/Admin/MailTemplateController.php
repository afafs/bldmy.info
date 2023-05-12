<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Validator;

class MailTemplateController extends Controller
{
    public function mailTemplates()
    {
      $templates = EmailTemplate::all();
  
      return view('admin.basic.email.mail_templates', compact('templates'));
    }
  
    public function editMailTemplate($id)
    {
      $templateInfo = EmailTemplate::findOrFail($id);
  
      return view('admin.basic.email.edit_mail_template', compact('templateInfo'));
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
  
      EmailTemplate::findOrFail($id)->update($request->except('email_type', 'email_body') + [
          'email_body' => clean($request->email_body)
      ]);
  
      $request->session()->flash('success', __('Updated successfully!'));
  
      return redirect()->back();
    }
}
