<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\UserCv;
use Auth;
use Session;
use Validator;

class CvController extends Controller
{
    public function cv()
    {
        $data['cvs'] = UserCv::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('user.cv.index', $data);
    }

    public function edit(UserCv $cv)
    {
        if ($cv->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }
        $data['cv'] = $cv;
        return view('user.cv.edit', $data);
    }

    public function store(Request $request)
    {

        $image = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'template' => 'required',
            'cv_name' => 'required|max:255',
            'direction' => 'required',
            'name' => 'required|max:255',
            'occupation' => 'required|max:255',
            'base_color' => 'required|max:20',
            'image' => [
                'required',
                function ($attribute, $value, $fail) use ($image, $allowedExts) {
                    if (!empty($image)) {
                        $ext = $image->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ]
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $cv = new UserCv();
        $cv->user_id = Auth::user()->id;
        $cv->template = $request->template;
        $cv->cv_name = $request->cv_name;
        $cv->direction = $request->direction;
        $cv->base_color = $request->base_color;
        if ($request->hasFile('image')) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/cv/');
            @mkdir($dir, 0775, true);
            $request->file('image')->move($dir, $filename);
            $cv->image = $filename;
        }
        $cv->name = clean($request->name);
        $cv->occupation = clean($request->occupation);

        $cv->save();

        $request->session()->flash('success', toastrMsg('Store_successfully!'));
        return 'success';
    }

    public function update(Request $request)
    {
        $image = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'template' => 'required',
            'cv_name' => 'required|max:255',
            'direction' => 'required',
            'name' => 'required|max:255',
            'occupation' => 'required|max:255',
            'base_color' => 'required|max:20',
            'image' => [
                function ($attribute, $value, $fail) use ($image, $allowedExts) {
                    if (!empty($image)) {
                        $ext = $image->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $cv = UserCv::find($request->cv_id);
        if ($cv->user_id != Auth::user()->id) {
            return;
        }
        $cv->cv_name = $request->cv_name;
        $cv->template = $request->template;
        $cv->direction = $request->direction;
        $cv->base_color = $request->base_color;
        if ($request->hasFile('image')) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/cv/');
            @mkdir($dir, 0775, true);
            $request->file('image')->move($dir, $filename);
            $cv->image = $filename;
        }
        $cv->name = clean($request->name);
        $cv->occupation = clean($request->occupation);

        $cv->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $cv = UserCv::where('user_id', Auth::user()->id)->where('id', $request->cv_id)->firstOrFail();
        if ($cv->user_id != Auth::user()->id) {
            return back();
        }
        @unlink(public_path('assets/front/img/user/cv/' . $cv->image));
        if ($cv->user_cv_sections()->count() > 0) {
            $cv->user_cv_sections()->delete();
        }
        $cv->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $cv = UserCv::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
            @unlink(public_path('assets/front/img/user/cv/' . $cv->image));
            if ($cv->user_cv_sections()->count() > 0) {
                $cv->user_cv_sections()->delete();
            }
            $cv->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }

    public function info(UserCv $cv)
    {

        if ($cv->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization_Failed');
            return back();
        }
        $data['cv'] = $cv;
        return view('user.cv.info', $data);
    }

    public function getinfos($id)
    {
        $cv = UserCv::find($id);
        $information = json_decode($cv->cv_information, true);
        return response()->json($information);
    }

    public function updateInfo(Request $request)
    {

        $rules = [
            'icons.*' => 'required',
            'colors.*' => 'required',
            'contents.*' => 'required',
        ];

        $messages = [
            'icons.*.required' => 'The Icon field cannot be empty',
            'colors.*.required' => 'The Color field cannot be empty',
            'contents.*.required' => 'The Content field cannot be empty'
        ];


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $cv = UserCv::find($request->cv_id);
        if ($cv->user_id != Auth::user()->id) {
            return;
        }

        $infoArr = [];
        $contents = $request->contents ? $request->contents : [];
        $icons = $request->icons ? $request->icons : [];
        $colors = $request->colors ? $request->colors : [];

        foreach ($contents as $key => $value) {
            $info = [
                'icon' => $icons["$key"],
                'color' => $colors["$key"],
                'content' => clean($contents["$key"])
            ];
            $infoArr[] = $info;
        }

        $cv->cv_information = json_encode($infoArr);

        $cv->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }

    public function updateContactTitle(Request $request)
    {
        $rules = [
            'contact_title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $cv = UserCv::find($request->cv_id);
        $cv->contact_title = clean($request->contact_title);
        $cv->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }
}
