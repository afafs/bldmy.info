<?php

namespace App\Http\Controllers\User;

use App;
use Auth;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\User\Follower;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('setlang');
    }

    public function index()
    {
        $user = Auth::user();
        $deLang = Language::where('user_id', Auth::id())->where('is_default', 1)->firstOrFail();
        $data['user'] = $user;
        $data['skills'] = $user->skills()->where('language_id', $deLang->id)->count();
        $data['portfolios'] = $user->portfolios()->where('language_id', $deLang->id)->count();
        $data['services'] = $user->services()->where('lang_id', $deLang->id)->count();
        $data['testimonials'] = $user->testimonials()->where('lang_id', $deLang->id)->count();
        $data['blogs'] = $user->blogs()->where('language_id', $deLang->id)->count();
        $data['job_experiences'] = $user->job_experiences()->where('lang_id', $deLang->id)->count();
        $data['achievements'] = $user->achievements()->where('language_id', $deLang->id)->count();
        $data['followers'] = Follower::where('following_id', Auth::id())->count();
        $data['followings'] = Follower::where('follower_id', Auth::id())->count();

        $data['memberships'] = Membership::query()->where('user_id', Auth::user()->id)
            ->orderBy('id', 'DESC')
            ->limit(10)->get();

        $data['users'] = [];
        $followingListIds = Follower::query()->where('follower_id', Auth::id())->pluck('following_id');
        if (count($followingListIds) > 0) {
            $data['users'] = User::whereIn('id', $followingListIds)->limit(10)->get();
        }

        $nextPackageCount = Membership::query()->where([
            ['user_id', Auth::id()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', Auth::id()],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', Auth::id()],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', Auth::id()],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', Auth::id()]
                ])->where(function ($query) use ($data) {
                    $query->where('start_date', '>=', $data['current_membership']->expire_date)
                        ->orWhere('transaction_details', '=', '"offline"');
                })->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;

        return view('user.dashboard', $data);
    }

    public function status(Request $request)
    {
        $user = Auth::user();
        $user->online_status = $request->value;
        $user->save();
        $msg = '';
        if ($request->value == 1) {
            $msg = "Profile has been made visible";
        } else {
            $msg = "Profile has been hidden";
        }
        Session::flash('success', $msg);
        return "success";
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,' . Auth::user()->id,
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'address' => 'required',
        ]);

        //--- Validation Section Ends
        $input = $request->all();
        $data = Auth::user();
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path('assets/front/img/user/'), $name);
            if ($data->photo != null) {
                @unlink(public_path('assets/front/img/user/' . $data->photo));
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function resetform()
    {
        return view('user.reset');
    }

    public function reset(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirmation_password' => 'required',
        ]);
        $user = Auth::user();
        if ($request->current_password) {
            if (Hash::check($request->current_password, $user->password)) {
                if ($request->new_password == $request->confirmation_password) {
                    $input['password'] = Hash::make($request->new_password);
                } else {
                    return back()->with('err', __('Confirm password does not match.'));
                }
            } else {
                return back()->with('err', __('Current password Does not match.'));
            }
        }

        $user->update($input);
        Session::flash('success', toastrMsg('Successfully_change_your_password'));
        return back();
    }

    public function changePass()
    {
        return view('user.changepass');
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'password.required' => 'The new password field is required',
            'password.confirmed' => "Password does'nt match"
        ];
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ], $messages);
        // if given old password matches with the password of this authenticated user...
        if (Hash::check($request->old_password, Auth::guard('web')->user()->password)) {
            $oldPassMatch = 'matched';
        } else {
            $oldPassMatch = 'not_matched';
        }
        if ($validator->fails() || $oldPassMatch == 'not_matched') {
            if ($oldPassMatch == 'not_matched') {
                $validator->errors()->add('oldPassMatch', true);
            }
            return redirect()->route('user.changePass')
                ->withErrors($validator);
        }

        // updating password in database...
        $user = App\Models\User::findOrFail(Auth::guard('web')->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        Session::flash('success', toastrMsg('Password_changed_successfully!'));

        return redirect()->back();
    }

    public function shippingdetails()
    {
        $user = Auth::user();
        return view('user.shipping_details', compact('user'));
    }

    public function shippingupdate(Request $request)
    {
        $request->validate([
            "shpping_fname" => 'required',
            "shpping_lname" => 'required',
            "shpping_email" => 'required',
            "shpping_number" => 'required',
            "shpping_city" => 'required',
            "shpping_state" => 'required',
            "shpping_address" => 'required',
            "shpping_country" => 'required',
        ]);


        Auth::user()->update($request->all());

        Session::flash('success', toastrMsg('Updated_successfully.'));
        return back();
    }

    public function billingdetails()
    {
        $user = Auth::user();
        return view('user.billing_details', compact('user'));
    }

    public function billingupdate(Request $request)
    {
        $request->validate([
            "billing_fname" => 'required',
            "billing_lname" => 'required',
            "billing_email" => 'required',
            "billing_number" => 'required',
            "billing_city" => 'required',
            "billing_state" => 'required',
            "billing_address" => 'required',
            "billing_country" => 'required',
        ]);

        Auth::user()->update($request->all());

        Session::flash('success', toastrMsg('Updated_successfully.'));
        return back();
    }

    public function changeTheme(Request $request)
    {
        return redirect()->back()->withCookie(cookie()->forever('user-theme', $request->theme));
    }


    // registerd customer 

    public function registerUsers()
    {
        $term = request('term');
        $data['current_language'] = Language::where([['is_default', 1], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
        $data['users'] = Customer::when($term, function ($query, $term) {
            $query->where('username', 'like', '%' . $term . '%')->orWhere('email', 'like', '%' . $term . '%');
        })->where('user_id', Auth::guard('web')->user()->id)->orderBy('id','desc')->paginate(10);
      
        return view('user.register_customer.index', $data);
    }

    public function changePassCstmr(Customer $customer)
    {
        if ($customer->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }
        $data['customer'] = $customer;
        return view('user.register_customer.changepass', $data);
    }

    public function updatePasswordCstmr(Request $request)
    {

        $messages = [
            'password.required' => 'The new password field is required',
            'password.confirmed' => "Password does'nt match"
        ];

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ], $messages);
        // if given old password matches with the password of this authenticated user...
        if (Hash::check($request->old_password, Customer::findOrFail($request->customer_id)->password)) {
            $oldPassMatch = 'matched';
        } else {
            $oldPassMatch = 'not_matched';
        }
        if ($validator->fails() || $oldPassMatch == 'not_matched') {
            if ($oldPassMatch == 'not_matched') {
                $validator->errors()->add('oldPassMatch', true);
            }
            return redirect()->route('register.customer.changePass')
                ->withErrors($validator);
        }
        // updating password in database...
        $user = Customer::findOrFail($request->customer_id);
        $user->password = bcrypt($request->password);
        $user->save();
        Session::flash('success', toastrMsg('Password_changed_successfully!'));
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $user = Customer::findOrFail($request->user_id);
        //user report delete/
        @unlink(public_path('assets/user/img/users/' . $user->image));
        $user->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $key => $id) {
            $user = Customer::findOrFail($id);
            @unlink(public_path('assets/user/img/users/' . $user->image));
            $user->delete();
        }
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return "success";
    }

    public function userban(Request $request)
    {
        $user = Customer::where('id', $request->user_id)->first();
        $user->update([
            'status' => $request->status,
        ]);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }
    public function emailStatus(Request $request)
    {
        $user = Customer::findOrFail($request->user_id);
        if ($user->email_verified_at) {
            $v = null;
        } else {
            $v = Carbon::now();
        }
        $user->update([
            'email_verified_at' => $v,
        ]);
        Session::flash('success', __('Email status updated for ') . $user->username);
        return back();
    }


    public function view(Customer $customer)
    {
        if ($customer->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', toastrMsg('Authorization_Failed'));
            return back();
        }
        $data['statuses'] = ([
            1 => 'Approved',
            0 => 'Pending',
            2 => 'Rejected',
        ]);
        $data['current_language'] = Language::where([['is_default', 1], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
        $data['user'] = $customer;
        // dd($user);
        return view('user.register_customer.details', $data);
    }
}
