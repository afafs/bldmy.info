<?php

namespace App\Http\Controllers\Front;


use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\BasicSetting;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User\UserEmailTemplate;
use App\Models\User\AppointmentBooking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    public function login(Request $request, $domain)
    {
        $user = getUser();
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        // when user have to redirect to check out page after login.
        if (
            $request->input('redirect_path') == 'checkout' &&
            !empty($request->input('digital_item'))
        ) {
            $hasDigitalProduct = $request->input('digital_item');
        }
        /**
         * when user have to redirect to product details page after login.
         * or, when user have to redirect to previous page for bookmark a post after login.
         */
        if (
            $request->input('redirect_path') == 'product-details' ||
            !empty($request->input('redirect_for'))
        ) {
            $request->session()->put('redirectTo', url()->previous());
        }


        return view('user-front.user.login', $data);
    }

    public function signup($domain)
    {
        $user = getUser();
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        return view('user-front.user.signup', $data);
    }

    public function forgetPassword($domain)
    {
        $user = getUser();
        $id = $user->id;
        $data['user'] = $user;

        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        return view('user-front.user.forget-password', $data);
    }


    public function loginSubmit(Request $request, $domain)
    {
        // at first, get the url from session which will be redirected after login
        if ($request->session()->has('redirect_link')) {
            $redirectURL = $request->session()->get('redirect_link');
        } else {
            $redirectURL = null;
        }
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // get the email and password which has provided by the user
        $credentials = $request->only('email', 'password', 'user_id');
        // login attempt
        if (Auth::guard('customer')->attempt($credentials)) {
            $authUser = Auth::guard('customer')->user();
            // first, check whether the user's email address verified or not
            if ($authUser->email_verified_at == null) {
                $request->session()->flash('error', 'Please, verify your email address.');
                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();
                return redirect()->back();
            }
            // second, check whether the user's account is active or not
            if ($authUser->status == 0) {
                $request->session()->flash('error', 'Sorry, your account has been deactivated.');
                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();
                return redirect()->back();
            }
            // otherwise, redirect auth user to next url
            if ($redirectURL == null) {
                return redirect()->route('customer.dashboard', getParam());
            } else {
                // before, redirect to next url forget the session value
                $request->session()->forget('redirect_link');
                return redirect($redirectURL);
            }
        } else {
            $request->session()->flash('error', 'The provided credentials do not match our records!');
            return redirect()->back();
        }
    }

    public function sendMail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                ]
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = Customer::where('email', $request->email)->first();
            // first, get the mail template information from db
            $mailTemplate = UserEmailTemplate::where('user_id', getUser()->id)->where('email_type', 'reset_password')->first();
            $mailSubject = $mailTemplate->email_subject;
            $mailBody = $mailTemplate->email_body;
            // second, send a password reset link to user via email
            $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();
            $websiteInfo = DB::table('user_basic_settings')->where('user_id',getUser()->id)
            ->select('website_title')
            ->first();
        
        $name = $user->first_name . ' ' . $user->last_name;
        $link = '<a href=' . route('customer.reset_password', getParam()) . '>Click Here</a>';
        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteInfo->website_title, $mailBody);
        // initialize a new mail
        $mail = new PHPMailer(true);
        // if smtp status == 1, then set some value for PHPMailer
        if ($info->is_smtp == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;
            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = $info->smtp_port;
        }
        // finally, add other information and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;
            $mail->send();
            $request->session()->flash('success', 'A mail has been sent to your email address.');
        } catch (Exception $e) {
            $request->session()->flash('error', 'Mail could not be sent!');
        }
        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        return redirect()->back();
    }
    public function resetPassword($domain)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        return view('user-front.user.reset-password', $data);
    }

    public function resetPasswordSubmit(Request $request, $domain)
    {
        $author = getUser();
        // get the user email from session
        $emailAddress = $request->session()->get('userEmail');

        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation failed.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = Customer::where('email', $emailAddress)->where('user_id', $author->id)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        $request->session()->flash('success', 'Password updated successfully.');
        return redirect()->route('customer.login', getParam());
    }

    public function appointments($domain)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        $data['authUser'] = Auth::guard('customer')->user();

        $data['appointments'] = AppointmentBooking::where('customer_id', Auth::guard('customer')->user()->id)
            ->orderBy('id', 'desc')
            ->get();
        return view('user-front.user.myappointments', $data);
    }


    public function appointmentDetails($domain, AppointmentBooking $appointment)
    {

        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }

        $data['authUser'] = Auth::guard('customer')->user();
        $data['appointment']  = $appointment;
        return view('user-front.user.appointment-details', $data);
    }


    public function signupSubmit(Request $request, $domain)
    {
        $user = getUser();
        $rules = [
            'username' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if (Customer::where('username', $value)->where('user_id', $user->id)->count() > 0) {
                        $fail('Username has already been taken');
                    }
                }
            ],
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) use ($user) {
                if (Customer::where('email', $value)->where('user_id', $user->id)->count() > 0) {
                    $fail('Email has already been taken');
                }
            }],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $customer = new Customer;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->user_id = $user->id;
        $customer->password = Hash::make($request->password);
        // first, generate a random string
        $randStr = Str::random(20);
        // second, generate a token
        $token = md5($randStr . $request->username . $request->email);
        $customer->verification_token = $token;
        $customer->save();
        // send a mail to user for verify his/her email address
        $this->sendVerificationMail($request, $token);
        return redirect()
            ->back()
            ->with('sendmail', 'We need to verify your email address. We have sent an email to  ' . $request->email . ' to verify your email address. Please click link in that email to continue.');
    }

    public function sendVerificationMail(Request $request, $token)
    {
        $user = getUser();
        // first get the mail template information from db
        $mailTemplate = UserEmailTemplate::where('email_type', 'email_verification')->where('user_id', $user->id)->first();
        $mailSubject = $mailTemplate->email_subject;
        $mailBody = $mailTemplate->email_body;
        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $websiteInfo = DB::table('basic_settings')
            ->select('website_title')
            ->first();

        $link = '<a href=' . route('customer.signup.verify', ['token' => $token, getParam()]) . '>Click Here</a>';
        // replace template's curly-brace string with actual data
        $mailBody = str_replace('{customer_name}', $request->username, $mailBody);
        $mailBody = str_replace('{verification_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteInfo->website_title, $mailBody);
        $userInfo = BasicSetting::where('user_id', $user->id)->select('email', 'from_name')->first();
        $email = $userInfo->email ?? $user->email;
        $name = $userInfo->from_name ?? $user->username;
        // initialize a new mail
        $mail = new PHPMailer(true);
        // if smtp status == 1, then set some value for PHPMailer
        if ($info->is_smtp == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;
            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = $info->smtp_port;
        }
        // finally, add other information and send the mail
        try {
            $mail->setFrom($info->from_mail, $name);
            $mail->addReplyTo($email);
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;
            $mail->send();
            $request->session()->flash('success', 'A verification mail has been sent to your email address.');
        } catch (Exception $e) {
            $request->session()->flash('error', 'Mail could not be sent!');
        }
        return;
    }
    public function signupVerify(Request $request, $domain, $token)
    {
        try {
            $user = Customer::where('verification_token', $token)->firstOrFail();
            // after verify user email, put "null" in the "verification token"
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'verification_token' => null
            ]);
            $request->session()->flash('success', 'Your email has verified.');
            // after email verification, authenticate this user
            Auth::guard('customer')->login($user);
            return redirect()->route('customer.dashboard', getParam());
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('error', 'Could not verify your email!');
            return redirect()->route('customer.signup', getParam());
        }
    }

    public function redirectToDashboard($domain)
    {
        $data['author'] = getUser();
        $data['language'] = $this->getUserCurrentLanguage($data['author']->id);
        $data['authUser'] = Auth::guard('customer')->user();

        $user = getUser();
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }


        return view('user-front.user.dashboard', $data);
    }

    public function editProfile()
    {
        $user = getUser();
        $data['authUser'] = Auth::guard('customer')->user();
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }
        return view('user-front.user.edit-profile', $data);
    }

    public function updateProfile(Request $request)
    {

        $authUser = Auth::guard('customer')->user();

        if ($request->hasFile('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/users/' . $authUser->image));

            // second, set a name for the new image and store it to local storage
            $proPic = $request->file('image');
            $picName = time() . '.' . $proPic->getClientOriginalExtension();
            $directory = public_path('assets/user/img/users/');

            @mkdir($directory, 0775, true);
            $proPic->move($directory, $picName);
        }

        $authUser->update($request->except('image') + [
            'image' => $request->exists('image') ? $picName : $authUser->image
        ]);

        $request->session()->flash('success', 'Your profile updated successfully.');

        return redirect()->back();
    }
    public function changePassword()
    {
        $user  = getUser();
        $id = $user->id;
        $data['user'] = $user;
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $ubs = BasicSetting::where('user_id', $user->id)->firstOrFail();
        if ($ubs->theme == 1 || $ubs->theme == 2) {
            $data['folder'] = "profile1";
        } elseif ($ubs->theme == 3) {
            $data['folder'] = "profile1.theme3";
        } elseif ($ubs->theme == 4) {
            $data['folder'] = "profile1.theme4";
        } elseif ($ubs->theme == 5) {
            $data['folder'] = "profile1.theme5";
        } elseif ($ubs->theme == 6 || $ubs->theme == 7 || $ubs->theme == 8) {
            $data['folder'] = 'profile1.theme' . $ubs->theme;
        } else {
            $data['folder'] = "profile";
        }

        $data['authUser'] = Auth::guard('customer')->user();

        return view('user-front.user.change-password', $data);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('customer')->user()->password)) {
                        $fail('Your password was not updated, since the provided current password does not match.');
                    }
                }
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation failed.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::guard('customer')->user();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', 'Password updated successfully.');

        return redirect()->back();
    }

    public function logoutSubmit(Request $request, $domain)
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login', getParam());
    }
}
