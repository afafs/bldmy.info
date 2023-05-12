<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Package;
use Illuminate\Http\Request;
use Validator;
use Session;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $data['coupons'] = Coupon::orderBy('id', 'DESC')->paginate(10);
        $data['packages'] = Package::where('status', '1')->get();
        return view('admin.packages.coupons.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required|unique:coupons',
            'type' => 'required',
            'value' => 'required',
            'start_date' => 'required',
            'maximum_uses_limit' => 'required',
            'end_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $input = $request->except('_token');
        $input['packages'] = json_encode($request->packages);

        Coupon::create($input);

        Session::flash('success', __('Store successfully!'));
        return "success";
    }

    public function edit($id)
    {
        $data['coupon'] = Coupon::findOrFail($id);
        $data['packages'] = Package::where('status', '1')->get();
        $data['selectedPackages'] = !empty($data['coupon']->packages) ? json_decode($data['coupon']->packages, true) : [];
        return view('admin.packages.coupons.edit', $data);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required|unique:coupons,code,' . $request->coupon_id,
            'type' => 'required',
            'value' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $input = $request->except('_token', 'coupon_id');

        $data = Coupon::find($request->coupon_id);
        $packages = !empty($request->packages) ? json_encode($request->packages) : NULL;
        $input['packages'] = $packages;
        $data->fill($input)->save();

        Session::flash('success', __('Updated successfully!'));
        return "success";
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->coupon_id);
        $coupon->delete();

        Session::flash('success', __('Deleted successfully!'));
        return back();
    }
}
