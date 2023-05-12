<?php

namespace App\Http\Controllers\User;

use App\Models\User\BasicSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicExtended as BE;
use App\Models\User\Language;
use App\Models\User\FormInput;
use App\Models\User\FormInputOption;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Validator;
use Session;

class FormController extends Controller
{
    // public function form(Request $request)
    // {

    //     $lang = Language::where([
    //         ['code', $request->language],
    //         ['user_id', Auth::guard('web')->user()->id]
    //     ])->firstOrFail();
    //     $data['lang_id'] = $lang->id;
    //     $data['abs'] = $lang->basic_setting;
    //     $data['inputs'] = FormInput::where([
    //         ['language_id', $lang->id],
    //         ['user_id', Auth::guard('web')->user()->id]
    //     ])->orderBy('order_number', 'ASC')->get();
    //     return view('user.quote.form', $data);
    // }

    public function orderUpdate(Request $request)
    {
        $ids = $request->ids;
        $orders = $request->orders;

        if (!empty($ids)) {
            foreach ($request->ids as $key => $id) {
                $input = FormInput::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
                $input->order_number = $orders["$key"];
                $input->save();
            }
        }
    }

    public function formstore(Request $request)
    {


        $inname = make_input_name($request->label);
        if ($request->category_id) {
            $inputs = FormInput::where([
                ['language_id', $request->language_id],
                ['user_id', Auth::guard('web')->user()->id],
                ['category_id', $request->category_id]
            ])->get();
        } else {
            $inputs = FormInput::where([
                ['language_id', $request->language_id],
                ['user_id', Auth::guard('web')->user()->id],
                ['category_id', null]
            ])->get();
        }
        $messages = [
            'options.*.required_if' => 'Options are required if field type is select dropdown/checkbox',
            'placeholder.required_unless' => 'The placeholder field is required ',
            'file_extensions.required_if' => 'The file extensions field is required '
        ];
        $rules = [
            'label' => [
                'required',
                function ($attribute, $value, $fail) use ($inname, $inputs) {
                    foreach ($inputs as $input) {
                        if (strtolower($input->name) == strtolower($inname)) {
                            $fail("Input field already exists.");
                        }
                    }
                },
            ],
            'file_extensions' => 'required_if:type,5',
            'placeholder' => 'required_unless:type,3,5',
            'type' => 'required',
            'options.*' => 'required_if:type,2,3'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $maxOrder = FormInput::where([
            ['language_id', $request->language_id],
            ['user_id', Auth::guard('web')->user()->id]
        ])->max('order_number');

        $input = new FormInput;
        $input->language_id = $request->language_id;
        $input->user_id = Auth::guard('web')->user()->id;
        $input->category_id = $request->category_id;
        $input->type = $request->type;
        $input->label = $request->label;
        $input->name = $inname;
        $input->placeholder = $request->placeholder;
        $input->required = $request->required;
        $input->searchable = $request->searchable;
        $input->order_number = $maxOrder + 1;
        $input->file_extensions = $request->file_extensions ?? null;
        $input->save();

        if ($request->type == 2 || $request->type == 3) {
            $options = $request->options;
            foreach ($options as $option) {
                $op = new FormInputOption;
                $op->form_input_id = $input->id;
                $op->name = $option;
                $op->save();
            }
        }

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    public function inputDelete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $input = FormInput::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->input_id)->firstOrFail();
        $input->form_input_options()->delete();
        $input->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function inputEdit($id)
    {
        $data['input'] = FormInput::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
        if (!empty($data['input']->form_input_options)) {
            $options = $data['input']->form_input_options;
            $data['options'] = $options;
            $data['counter'] = count($options);
        }
        return view('user.appointment.form-edit', $data);
    }

    public function inputUpdate(Request $request)
    {
        $inname = make_input_name($request->label);
        $input = FormInput::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->input_id)->firstOrFail();
        if ($input->category_id) {
            $inputs = FormInput::where([
                ['language_id', $input->language_id],
                ['user_id', Auth::guard('web')->user()->id],
                ['category_id', $request->category_id]
            ])->get();
        } else {
            $inputs = FormInput::where([
                ['language_id', $input->language_id],
                ['user_id', Auth::guard('web')->user()->id],
                ['category_id', null]
            ])->get();
        }
        // return $request->options;
        $messages = [
            'options.required_if' => 'Options are required',
            'placeholder.required_unless' => 'Placeholder is required',
            'file_extensions.required_if' => 'The file extensions field is required '
        ];

        $rules = [
            'label' => [
                'required',
                function ($attribute, $value, $fail) use ($inname, $inputs, $input) {
                    foreach ($inputs as $in) {
                        if (strtolower($in->name) == strtolower($inname) && strtolower($inname) != strtolower($input->name)) {
                            $fail("Input field already exists.");
                        }
                    }
                },
            ],

            'file_extensions' => 'required_if:type,5',
            'placeholder' => 'required_unless:type,3,5',
            'options' => [
                'required_if:type,2,3',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type == 2 || $request->type == 3) {
                        foreach ($request->options as $option) {
                            if (empty($option)) {
                                $fail('All option fields are required.');
                            }
                        }
                    }
                },
            ]
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $input->label = $request->label;
        $input->name = $inname;

        // if input is checkbox then placeholder is not required
        if ($request->type != 3 && $request->type != 5) {
            $input->placeholder = $request->placeholder;
        }
        $input->required = $request->required;
        $input->searchable = $request->searchable;
        $input->file_extensions = $request->file_extensions;
        $input->save();

        if ($request->type == 2 || $request->type == 3) {
            $input->form_input_options()->delete();
            $options = $request->options;
            foreach ($options as  $option) {
                $op = new FormInputOption;
                $op->form_input_id = $input->id;
                $op->name = $option;
                $op->save();
            }
        }
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function options($id)
    {
        return FormInputOption::where('form_input_id', $id)->get();
    }

    public function all()
    {
        $data['quotes'] = Quote::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('user.quote.quote', $data);
    }

    public function pending()
    {
        $data['quotes'] = Quote::where([
            ['status', 0],
            ['user_id', Auth::guard('web')->user()->id]
        ])->orderBy('id', 'DESC')->paginate(10);
        return view('user.quote.quote', $data);
    }

    public function processing()
    {
        $data['quotes'] = Quote::where([
            ['status', 1],
            ['user_id', Auth::guard('web')->user()->id]
        ])->orderBy('id', 'DESC')->paginate(10);
        return view('user.quote.quote', $data);
    }

    public function completed()
    {
        $data['quotes'] = Quote::where([
            ['status', 2],
            ['user_id', Auth::guard('web')->user()->id]
        ])->orderBy('id', 'DESC')->paginate(10);
        return view('user.quote.quote', $data);
    }

    public function rejected()
    {
        $data['quotes'] = Quote::where([
            ['status', 3],
            ['user_id', Auth::guard('web')->user()->id]
        ])->orderBy('id', 'DESC')->paginate(10);
        return view('user.quote.quote', $data);
    }

    public function status(Request $request)
    {
        $quote = Quote::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->quote_id)->firstOrFail();
        $quote->status = $request->status;
        $quote->save();

        Session::flash('success', toastrMsg('Status_Changed_successfully!'));
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

        $be = BE::first();
        $from = Auth::guard('web')->user()->company_name;

        $sub = $request->subject;
        $msg = $request->message;
        $to = $request->email;


        // Send Mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host = $be->smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $be->smtp_username;
                $mail->Password = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $from);
                $mail->addReplyTo(Auth::guard('web')->user()->email, $from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body = $msg;

                $mail->send();
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        } else {
            try {

                //Recipients
                $mail->setFrom($be->from_mail, $from);
                $mail->addReplyTo(Auth::guard('web')->user()->email, $from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body = $msg;

                $mail->send();
            } catch (Exception $e) {
            }
        }

        Session::flash('success', toastrMsg('Mail_sent_successfully!'));
        return "success";
    }

    public function delete(Request $request)
    {
        Quote::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->quote_id)->firstOrFail()->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            Quote::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail()->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }
}
