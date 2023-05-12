<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Membership;
use App\Models\BasicSetting;
use Illuminate\Http\Request;
use App\Models\BasicExtended;
use App\Models\User\Language;
use App\Models\OfflineGateway;
use App\Models\PaymentGateway;
use App\Http\Helpers\MegaMailer;
use App\Models\User\HomePageText;
use App\Models\User\UserDay as Day;
use App\Models\User\UserPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserPaymentGateway;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicSetting as UserBasicSetting;

class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;

        $users = User::when($term, function ($query, $term) {
            $query->where('username', 'like', '%' . $term . '%')->orWhere('email', 'like', '%' . $term . '%');
        })->orderBy('id', 'DESC')->paginate(10);

        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $gateways = $online->merge($offline);
        $packages = Package::query()->where('status', '1')->get();

        return view('admin.register_user.index', compact('users', 'gateways', 'packages'));
    }

    public function view($id)
    {
        $user = User::findOrFail($id);
        $packages = Package::query()->where('status', '1')->get();

        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $gateways = $online->merge($offline);

        return view('admin.register_user.details', compact('user', 'packages', 'gateways'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'package_id' => 'required',
            'payment_gateway' => 'required',
            'online_status' => 'required'
        ];
        $messages = [
            'package_id.required' => 'The package field is required',
            'online_status.required' => 'The publicly hidden field is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $user = User::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = User::create([
                'email' => $request['email'],
                'username' => $request['username'],
                'password' => bcrypt($request['password']),
                'online_status' => $request["online_status"],
                'status' => 1,
                'email_verified' => 1,
            ]);
            UserBasicSetting::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'website_title' => $user->username,
                'from_name' => $user->username,
            ]);
        }
        if ($user) {
            $deLang = Language::firstOrFail();
            $langCount = Language::where('user_id', $user->id)->where('is_default', 1)->count();
            if ($langCount == 0) {
                $language = Language::create([
                    'name' => $deLang->name,
                    'code' => $deLang->code,
                    'is_default' => 1,
                    'rtl' => $deLang->rtl,
                    'user_id' => $user->id,
                    'keywords' => $deLang->keywords
                ]);
                HomePageText::create([
                    'user_id' => $user->id,
                    'language_id' => $language->id
                ]);
            }
            // create Day table 
            $days = [
                'Sunday' => 0,
                'Monday' => 1,
                'Tuesday' => 2,
                'Wednesday' => 3,
                'Thursday' => 4,
                'Friday' => 5,
                'Saturday' => 6
            ];
            foreach ($days as $key => $index) {
                Day::create([
                    'user_id' => $user->id,
                    'day' => $key,
                    'index' => $index,
                ]);
            }

            // create payment gateways 
            $payment_keywords = ['flutterwave', 'razorpay', 'paytm', 'paystack', 'instamojo', 'stripe', 'paypal', 'mollie', 'mercadopago', 'authorize.net'];
            foreach ($payment_keywords as $key => $value) {
                UserPaymentGateway::create([
                    'title' => null,
                    'user_id' => $user->id,
                    'details' => null,
                    'keyword' => $value,
                    'subtitle' => null,
                    'name' => ucfirst($value),
                    'type' => 'automatic',
                    'information' => null
                ]);
            }
            // create email template 
            $templates = ['email_verification', 'appointment_booking_notification', 'reset_password'];
            foreach ($templates as $key => $val) {
                UserEmailTemplate::create([
                    'user_id' => $user->id,
                    'email_type' => $val,
                    'email_subject' => str_replace('_', ' ', $val),
                    'email_body' => '<p></p>',
                ]);
            }
            $package = Package::find($request['package_id']);
            $be = BasicExtended::first();
            $bs = BasicSetting::select('website_title')->first();
            $transaction_id = UserPermissionHelper::uniqidReal(8);

            $startDate = Carbon::today()->format('Y-m-d');
            if ($package->term === "monthly") {
                $endDate = Carbon::today()->addMonth()->format('Y-m-d');
            } elseif ($package->term === "yearly") {
                $endDate = Carbon::today()->addYear()->format('Y-m-d');
            } elseif ($package->term === "lifetime") {
                $endDate = Carbon::maxValue()->format('d-m-Y');
            }
            Membership::create([
                'price' => $package->price,
                'currency' => $be->base_currency_text ? $be->base_currency_text : "USD",
                'currency_symbol' => $be->base_currency_symbol ? $be->base_currency_symbol : $be->base_currency_text,
                'payment_method' => $request["payment_gateway"],
                'transaction_id' => $transaction_id ? $transaction_id : 0,
                'status' => 1,
                'is_trial' => 0,
                'trial_days' => 0,
                'receipt' => $request["receipt_name"] ? $request["receipt_name"] : null,
                'transaction_details' => null,
                'settings' => json_encode($be),
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'start_date' => Carbon::parse($startDate),
                'expire_date' => Carbon::parse($endDate),
            ]);
            $package = Package::findOrFail($request['package_id']);
            $features = json_decode($package->features, true);
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";

            UserPermission::create([
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'permissions' => json_encode($features)
            ]);


            $requestData = [
                'start_date' => $startDate,
                'expire_date' => $endDate,
                'payment_method' => $request['payment_gateway']
            ];
            $file_name = $this->makeInvoice($requestData, "membership", $user, null, $package->price, $request['payment_gateway'], null, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

            $mailer = new MegaMailer();
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $startDate->toFormattedDateString(),
                'expire_date' => $endDate->toFormattedDateString(),
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => 'registration_with_premium_package',
                'type' => 'registrationWithPremiumPackage'
            ];
            $mailer->mailFromAdmin($data);
        }

        Session::flash('success', __('Store successfully!'));
        return "success";
    }
    public function secretLogin(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (Auth::guard('web')->login($user, true)) {
            return redirect()->route('user-dashboard')
                ->withSuccess('You have Successfully loggedin');
        }
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    public function userban(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->status = $request->status;
        $user->save();
        Session::flash('success', __('Updated successfully!'));
        return back();
    }


    public function emailStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update([
            'email_verified' => $request->email_verified,
        ]);

        Session::flash('success', __('Email status updated for ') . $user->username);
        return back();
    }

    public function userFeatured(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->featured = $request->featured;
        $user->feature_time = now();
        $user->save();
        Session::flash('success', __('Updated successfully!'));
        return back();
    }

    public function userTemplate(Request $request)
    {
        // return $request;
        if ($request->template == 1) {
            $prevImg = $request->file('preview_image');
            $allowedExts = array('jpg', 'png', 'jpeg');

            $rules = [
                'serial_number' => 'required|integer',
                'preview_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                        if (!empty($prevImg)) {
                            $ext = $prevImg->getClientOriginalExtension();
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg image is allowed");
                            }
                        }
                    },
                ]
            ];


            $request->validate($rules);
        }

        $user = User::where('id', $request->user_id)->first();

        if ($request->template == 1) {
            if ($request->hasFile('preview_image')) {
                @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
                $filename = uniqid() . '.' . $prevImg->getClientOriginalExtension();
                $dir = public_path('assets/front/img/template-previews/');
                @mkdir($dir, 0775, true);
                $request->file('preview_image')->move($dir, $filename);
                $user->template_img = $filename;
            }
            $user->template_serial_number = $request->serial_number;
        } else {
            @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
            $user->template_img = NULL;
            $user->template_serial_number = 0;
        }
        $user->preview_template = $request->template;
        $user->save();
        Session::flash('success', __('Status updated successfully!'));
        return back();
    }

    public function userUpdateTemplate(Request $request)
    {
        $prevImg = $request->file('preview_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'serial_number' => 'required|integer',
            'preview_image' => [
                function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                    if (!empty($prevImg)) {
                        $ext = $prevImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ]
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = User::where('id', $request->user_id)->first();


        if ($request->hasFile('preview_image')) {
            @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
            $filename = uniqid() . '.' . $prevImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/template-previews/');
            @mkdir($dir, 0775, true);
            $request->file('preview_image')->move($dir, $filename);
            $user->template_img = $filename;
        }
        $user->template_serial_number = $request->serial_number;
        $user->save();


        Session::flash('success', __('Status updated successfully!'));
        return "success";
    }


    public function changePass($id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.register_user.password', $data);
    }


    public function updatePassword(Request $request)
    {

        $messages = [
            'npass.required' => 'New password is required',
            'cfpass.required' => 'Confirm password is required',
        ];

        $request->validate([
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);


        $user = User::findOrFail($request->user_id);
        if ($request->npass == $request->cfpass) {
            $input['password'] = Hash::make($request->npass);
        } else {
            return back()->with('warning', __('Confirm password does not match.'));
        }

        $user->update($input);

        Session::flash('success', __('Password update for ') . $user->username);
        return back();
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->testimonials()->count() > 0) {
            $testimonials = $user->testimonials()->get();
            foreach ($testimonials as $key => $tstm) {
                @unlink(public_path('assets/front/img/user/testimonials/' . $tstm->image));
                $tstm->delete();
            }
        }

        if ($user->social_media()->count() > 0) {
            $user->social_media()->delete();
        }

        if ($user->skills()->count() > 0) {
            $user->skills()->delete();
        }

        if ($user->services()->count() > 0) {
            $services = $user->services()->get();
            foreach ($services as $key => $service) {
                @unlink(public_path('assets/front/img/user/services/' . $service->image));
                $service->delete();
            }
        }

        if ($user->seos()->count() > 0) {
            $user->seos()->delete();
        }

        if ($user->portfolios()->count() > 0) {
            $portfolios = $user->portfolios()->get();
            foreach ($portfolios as $key => $portfolio) {
                @unlink(public_path('assets/front/img/user/portfolios/' . $portfolio->image));
                if ($portfolio->portfolio_images()->count() > 0) {
                    foreach ($portfolio->portfolio_images as $key => $pi) {
                        @unlink(public_path('assets/front/img/user/portfolios/' . $pi->image));
                        $pi->delete();
                    }
                }
                $portfolio->delete();
            }
        }

        if ($user->portfolioCategories()->count() > 0) {
            $user->portfolioCategories()->delete();
        }

        if ($user->permission()->count() > 0) {
            $user->permission()->delete();
        }

        if ($user->languages()->count() > 0) {
            $user->languages()->delete();
        }

        if ($user->home_page_texts()->count() > 0) {
            $homeTexts = $user->home_page_texts()->get();
            foreach ($homeTexts as $key => $homeText) {
                @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->hero_image));
                @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->about_image));
                @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->skills_image));
                @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->achievement_image));
                $homeText->delete();
            }
        }

        if ($user->educations()->count() > 0) {
            $user->educations()->delete();
        }

        if ($user->blog_categories()->count() > 0) {
            $user->blog_categories()->delete();
        }

        if ($user->blogs()->count() > 0) {
            $blogs = $user->blogs()->get();
            foreach ($blogs as $key => $blog) {
                @unlink(public_path('assets/front/img/user/blogs/' . $blog->image));
                $blog->delete();
            }
        }

        if ($user->basic_setting()->count() > 0) {
            $bs = $user->basic_setting;
            @unlink(public_path('assets/front/img/user/' . $bs->logo));
            @unlink(public_path('assets/front/img/user/' . $bs->preloader));
            @unlink(public_path('assets/front/img/user/' . $bs->favicon));
            @unlink(public_path('assets/front/img/user/cv/' . $bs->cv));
            @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_image));
            @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_inserted_image));
            $bs->delete();
        }

        if ($user->achievements()->count() > 0) {
            $user->achievements()->delete();
        }

        if ($user->memberships()->count() > 0) {
            foreach ($user->memberships as $key => $membership) {
                @unlink(public_path('assets/front/img/membership/receipt/' . $membership->receipt));
                $membership->delete();
            }
        }

        if ($user->job_experiences()->count() > 0) {
            $user->job_experiences()->delete();
        }

        if ($user->custom_domains()->count() > 0) {
            $user->custom_domains()->delete();
        }



        if ($user->cvs()->count() > 0) {
            $cvs = $user->cvs;
            foreach ($cvs as $key => $cv) {
                if ($cv->user_cv_sections()->count() > 0) {
                    $cv->user_cv_sections()->delete();
                }
                @unlink(public_path('assets/front/img/user/cv/' . $cv->image));
                $cv->delete();
            }
        }

        if ($user->qr_codes()->count() > 0) {
            $qrs = $user->qr_codes;
            foreach ($qrs as $key => $qr) {
                @unlink(public_path('assets/front/img/user/qr/' . $qr->image));
                $qr->delete();
            }
        }

        if ($user->vcards()->count() > 0) {
            $vcards = $user->vcards;
            foreach ($vcards as $key => $vcard) {
                @unlink(public_path('assets/front/img/user/vcard/' . $vcard->profile_image));
                @unlink(public_path('assets/front/img/user/vcard/' . $vcard->cover_image));
                $vcard->delete();
            }
        }
        // Appointment related delations start
        if ($user->appointments()->count() > 0) {
            $user->appointments()->delete();
        }
        if ($user->appointment_categories()->count() > 0) {
            $categories = $user->appointment_categories;
            foreach ($categories as $key => $cate) {
                @unlink(public_path('assets/user/img/category/' . $cate->image));
                $cate->delete();
            }
        }
        if ($user->form_inputs()->count() > 0) {
            $form_inputs = $user->form_inputs;
            foreach ($form_inputs as $key => $forminput) {
                if ($forminput->form_input_options()->count() > 0) {
                    $forminput->form_input_options()->delete();
                }
                $user->form_inputs()->delete();
            }
        }
        if ($user->email_templates()->count() > 0) {
            $user->email_templates()->delete();
        }
        if ($user->holidays()->count() > 0) {
            $user->holidays()->delete();
        }
        if ($user->offline_payment_gateways()->count() > 0) {
            $user->offline_payment_gateways()->delete();
        }
        if ($user->payment_gateways()->count() > 0) {
            $user->payment_gateways()->delete();
        }
        if ($user->days()->count() > 0) {
            $user->days()->delete();
        }
        // Appointment related delations end

        @unlink(public_path('assets/front/img/user/' . $user->photo));
        $user->delete();

        Session::flash('success', __('Deleted successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::findOrFail($id);

            if ($user->testimonials()->count() > 0) {
                $testimonials = $user->testimonials()->get();
                foreach ($testimonials as $key => $tstm) {
                    @unlink(public_path('assets/front/img/user/testimonials/' . $tstm->image));
                    $tstm->delete();
                }
            }

            if ($user->social_media()->count() > 0) {
                $user->social_media()->delete();
            }

            if ($user->skills()->count() > 0) {
                $user->skills()->delete();
            }

            if ($user->services()->count() > 0) {
                $services = $user->services()->get();
                foreach ($services as $key => $service) {
                    @unlink(public_path('assets/front/img/user/services/' . $service->image));
                    $service->delete();
                }
            }

            if ($user->seos()->count() > 0) {
                $user->seos()->delete();
            }

            if ($user->portfolios()->count() > 0) {
                $portfolios = $user->portfolios()->get();
                foreach ($portfolios as $key => $portfolio) {
                    @unlink(public_path('assets/front/img/user/portfolios/' . $portfolio->image));
                    if ($portfolio->portfolio_images()->count() > 0) {
                        foreach ($portfolio->portfolio_images as $key => $pi) {
                            @unlink(public_path('assets/front/img/user/portfolios/' . $pi->image));
                            $pi->delete();
                        }
                    }
                    $portfolio->delete();
                }
            }

            if ($user->portfolioCategories()->count() > 0) {
                $user->portfolioCategories()->delete();
            }

            if ($user->permission()->count() > 0) {
                $user->permission()->delete();
            }

            if ($user->languages()->count() > 0) {
                $user->languages()->delete();
            }

            if ($user->home_page_texts()->count() > 0) {
                $homeTexts = $user->home_page_texts()->get();
                foreach ($homeTexts as $key => $homeText) {
                    @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->hero_image));
                    @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->about_image));
                    @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->skills_image));
                    @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->achievement_image));
                    $homeText->delete();
                }
            }

            if ($user->educations()->count() > 0) {
                $user->educations()->delete();
            }

            if ($user->blog_categories()->count() > 0) {
                $user->blog_categories()->delete();
            }

            if ($user->blogs()->count() > 0) {
                $blogs = $user->blogs()->get();
                foreach ($blogs as $key => $blog) {
                    @unlink(public_path('assets/front/img/user/blogs/' . $blog->image));
                    $blog->delete();
                }
            }

            if ($user->basic_setting()->count() > 0) {
                $bs = $user->basic_setting;
                @unlink(public_path('assets/front/img/user/' . $bs->logo));
                @unlink(public_path('assets/front/img/user/' . $bs->preloader));
                @unlink(public_path('assets/front/img/user/' . $bs->favicon));
                @unlink(public_path('assets/front/img/user/cv/' . $bs->cv));
                @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_image));
                @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_inserted_image));
                $bs->delete();
            }

            if ($user->achievements()->count() > 0) {
                $user->achievements()->delete();
            }

            if ($user->memberships()->count() > 0) {
                foreach ($user->memberships as $key => $membership) {
                    @unlink(public_path('assets/front/img/membership/receipt/' . $membership->receipt));
                    $membership->delete();
                }
            }

            if ($user->job_experiences()->count() > 0) {
                $user->job_experiences()->delete();
            }

            if ($user->custom_domains()->count() > 0) {
                $user->custom_domains()->delete();
            }

            if ($user->cvs()->count() > 0) {
                $cvs = $user->cvs;
                foreach ($cvs as $key => $cv) {
                    if ($cv->user_cv_sections()->count() > 0) {
                        $cv->user_cv_sections()->delete();
                    }
                    @unlink(public_path('assets/front/img/user/cv/' . $cv->image));
                    $cv->delete();
                }
            }

            if ($user->qr_codes()->count() > 0) {
                $qrs = $user->qr_codes;
                foreach ($qrs as $key => $qr) {
                    @unlink(public_path('assets/front/img/user/qr/' . $qr->image));
                    $qr->delete();
                }
            }

            if ($user->vcards()->count() > 0) {
                $vcards = $user->vcards;
                foreach ($vcards as $key => $vcard) {
                    @unlink(public_path('assets/front/img/user/vcard/' . $vcard->profile_image));
                    @unlink(public_path('assets/front/img/user/vcard/' . $vcard->cover_image));
                    $vcard->delete();
                }
            }

            // Appointment related delations start
            if ($user->appointments()->count() > 0) {
                $user->appointments()->delete();
            }
            if ($user->appointment_categories()->count() > 0) {
                $categories = $user->appointment_categories;
                foreach ($categories as $key => $cate) {
                    @unlink(public_path('assets/user/img/category/' . $cate->image));
                    $cate->delete();
                }
            }
            if ($user->form_inputs()->count() > 0) {
                $form_inputs = $user->form_inputs;
                foreach ($form_inputs as $key => $forminput) {
                    if ($forminput->form_input_options()->count() > 0) {
                        $forminput->form_input_options()->delete();
                    }
                    $user->form_inputs()->delete();
                }
            }
            if ($user->email_templates()->count() > 0) {
                $user->email_templates()->delete();
            }
            if ($user->holidays()->count() > 0) {
                $user->holidays()->delete();
            }
            if ($user->offline_payment_gateways()->count() > 0) {
                $user->offline_payment_gateways()->delete();
            }
            if ($user->payment_gateways()->count() > 0) {
                $user->payment_gateways()->delete();
            }
            if ($user->days()->count() > 0) {
                $user->days()->delete();
            }
            // Appointment related delations end

            @unlink(public_path('assets/front/img/user/' . $user->photo));
            $user->delete();
        }

        Session::flash('success', __('Deleted successfully!'));
        return "success";
    }

    public function removeCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $currMembership = UserPermissionHelper::currMembOrPending($userId);
        $currPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $today = Carbon::now();

        // just expire the current package
        $currMembership->expire_date = $today->subDay();
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // if next package exists
        if (!empty($nextMembership)) {
            $nextPackage = Package::find($nextMembership->package_id);

            $nextMembership->start_date = Carbon::parse(Carbon::today()->format('d-m-Y'));
            if ($nextPackage->term == 'monthly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addYear()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'lifetime') {
                $nextMembership->expire_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->save();
        }

        $this->sendMail(NULL, NULL, $request->payment_method, $user, $bs, $be, 'admin_removed_current_package', NULL, $currPackage->title);

        Session::flash('success', __('Current Package removed successfully!'));
        return back();
    }


    public function sendMail($memb, $package, $paymentMethod, $user, $bs, $be, $mailType, $replacedPackage = NULL, $removedPackage = NULL)
    {

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $activation = $memb->start_date;
            $expire = $memb->expire_date;
            $info['start_date'] = $activation->toFormattedDateString();
            $info['expire_date'] = $expire->toFormattedDateString();
            $info['payment_method'] = $paymentMethod;

            $file_name = $this->makeInvoice($info, "membership", $user, NULL, $package->price, "Stripe", $user->phone, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);
        }

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $user->email,
            'toName' => $user->fname,
            'username' => $user->username,
            'website_title' => $bs->website_title,
            'templateType' => $mailType
        ];

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $data['package_title'] = $package->title;
            $data['package_price'] = ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : '');
            $data['activation_date'] = $activation->toFormattedDateString();
            $data['expire_date'] = Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString();
            $data['membership_invoice'] = $file_name;
        }
        if ($mailType != 'admin_removed_current_package' || $mailType != 'admin_removed_next_package') {
            $data['removed_package_title'] = $removedPackage;
        }

        if (!empty($replacedPackage)) {
            $data['replaced_package'] = $replacedPackage;
        }

        $mailer->mailFromAdmin($data);
    }


    public function changeCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $currMembership = UserPermissionHelper::currMembOrPending($userId);
        $nextMembership = UserPermissionHelper::nextMembership($userId);

        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $selectedPackage = Package::find($request->package_id);

        // if the user has a next package to activate & selected package is 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term == 'lifetime') {
            Session::flash('membership_warning', 'To add a Lifetime package as Current Package, You have to remove the next package');
            return back();
        }

        // expire the current package
        $currMembership->expire_date = Carbon::parse(Carbon::now()->subDay()->format('d-m-Y'));
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        // if the user has a next package to activate & selected package is not 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term != 'lifetime') {
            $nextPackage = Package::find($nextMembership->package_id);

            // calculate & store next membership's start_date
            $nextMembership->start_date = Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'));

            // calculate & store expire date for next membership
            if ($nextPackage->term == 'monthly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addYear()->format('d-m-Y'));
            } else {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->expire_date = $exDate;
            $nextMembership->save();
        }


        $currentPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_changed_current_package', $currentPackage->title);


        Session::flash('success', __('Current Package changed successfully!'));
        return back();
    }

    public function addCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $selectedPackage = Package::find($request->package_id);

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_added_current_package');

        Session::flash('success', __('Current Package has been added successfully!'));
        return back();
    }

    public function removeNextPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        $nextPackage = Package::select('title')->findOrFail($nextMembership->package_id);


        $this->sendMail(NULL, NULL, $request->payment_method, $user, $bs, $be, 'admin_removed_next_package', NULL, $nextPackage->title);

        Session::flash('success', __('Next Package removed successfully!'));
        return back();
    }

    public function changeNextPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $bs = BasicSetting::select('website_title')->first();
        $be = BasicExtended::first();
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        $nextPackage = Package::find($nextMembership->package_id);
        $selectedPackage = Package::find($request->package_id);

        $prevStartDate = $nextMembership->start_date;
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::parse($prevStartDate)->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::parse($prevStartDate)->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        }

        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse($prevStartDate),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);
        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_changed_next_package', $nextPackage->title);
        Session::flash('success', __('Next Package changed successfully!'));
        return back();
    }

    public function addNextPackage(Request $request)
    {

        $userId = $request->user_id;
        $hasPendingMemb = UserPermissionHelper::hasPendingMembership($userId);
        if ($hasPendingMemb) {
            Session::flash('membership_warning', 'This user already has a Pending Package. Please take an action (change / remove / approve / reject) for that package first.');
            return back();
        }

        $currMembership = UserPermissionHelper::userPackage($userId);
        $currPackage = Package::find($currMembership->package_id);
        $be = BasicExtended::first();
        $user = User::findOrFail($userId);
        $bs = BasicSetting::select('website_title')->first();
        $selectedPackage = Package::find($request->package_id);
        if ($currMembership->is_trial == 1) {
            Session::flash('membership_warning', 'If your current package is trial package, then you have to change / remove the current package first.');
            return back();
        }


        // if current package is not lifetime package
        if ($currPackage->term != 'lifetime') {
            // calculate expire date for selected package
            if ($selectedPackage->term == 'monthly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addMonth()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'yearly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addYear()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'lifetime') {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            // store a new membership for selected package
            $selectedMemb = Membership::create([
                'price' => $selectedPackage->price,
                'currency' => $be->base_currency_text,
                'currency_symbol' => $be->base_currency_symbol,
                'payment_method' => $request->payment_method,
                'transaction_id' => uniqid(),
                'status' => 1,
                'receipt' => NULL,
                'transaction_details' => NULL,
                'settings' => json_encode($be),
                'package_id' => $selectedPackage->id,
                'user_id' => $userId,
                'start_date' => Carbon::parse(Carbon::parse($currMembership->expire_date)->addDay()->format('d-m-Y')),
                'expire_date' => Carbon::parse($exDate),
                'is_trial' => 0,
                'trial_days' => 0,
            ]);

            $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_added_next_package');
        } else {
            Session::flash('membership_warning', 'If your current package is lifetime package, then you have to change / remove the current package first.');
            return back();
        }


        Session::flash('success', __('Next Package has been added successfully!'));
        return back();
    }

    public function changeOnlineStatus(Request $request,$id)
    {
        User::find($id)->update([
            'online_status' => $request->online_status
        ]);

        Session::flash('success', __('Publicly Hidden Status Changed successfully!'));
        return back();
    }
}
