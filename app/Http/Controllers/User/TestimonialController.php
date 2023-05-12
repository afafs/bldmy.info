<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\UserTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Purifier;
use Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index(Request $request)
    {
        if ($request->has('language')) {
            $lang = Language::where([
                ['code', $request->language],
                ['user_id', Auth::id()]
            ])->first();
            Session::put('currentLangCode', $request->language);
        } else {
            $lang = Language::where([
                ['is_default', 1],
                ['user_id', Auth::id()]
            ])
                ->first();
            Session::put('currentLangCode', $lang->codel);
        }
        $data['testimonials'] = UserTestimonial::where([
            ['lang_id', '=', $lang->id],
            ['user_id', '=', Auth::id()],
        ])
            ->orderBy('id', 'DESC')
            ->get();
        return view('user.testimonial.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $messages = [
            'name.required' => 'The title field is required',
            'user_language_id.required' => 'The Language field is required',
            'content.required' => 'The content field is required',
            'serial_number.required' => 'The serial number field is required',
            'image.required' => 'The image field is required',
        ];

        $rules = [
            'name' => 'required|max:255',
            'user_language_id' => 'required',
            'content' => 'required',
            'serial_number' => 'required|integer',
            'image' => [
                'required',
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $input = $request->all();
        $input['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $directory = public_path('assets/front/img/user/testimonials/');
            if (!file_exists($directory)) mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $filename);
            $input['image'] = $filename;
        }
        $input['content'] = Purifier::clean($request->content);
        $input['lang_id'] = $request->user_language_id;
        $blog = new UserTestimonial();
        $blog->create($input);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTestimonial $testimonial)
    {

        if ($testimonial->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }

        $data['testimonial'] = $testimonial;
        return view('user.testimonial.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $messages = [
            'name.required' => 'The title field is required',
            'content.required' => 'The content field is required',
            'serial_number.required' => 'The serial number field is required',
            'image.required' => 'The image field is required',
        ];

        $rules = [
            'name' => 'required|max:255',
            'content' => 'required',
            'serial_number' => 'required|integer',
            'image' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $service = UserTestimonial::findOrFail($request->id);
        if ($service->user_id != Auth::user()->id) {
            return;
        }
        $input = $request->all();
        $input['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $directory = public_path('assets/front/img/user/testimonials/');
            $request->file('image')->move($directory, $filename);
            if (file_exists($directory . $service->image)) {
                @unlink($directory . $service->image);
            }
            $input['image'] = $filename;
        }
        $input['content'] = Purifier::clean($request->content);
        $service->update($input);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $tstm = UserTestimonial::where('user_id', Auth::user()->id)->where('id', $request->id)->firstOrFail();
        if (file_exists(public_path('assets/front/img/user/testimonials/' . $tstm->image))) {
            @unlink(public_path('assets/front/img/user/testimonials/' . $tstm->image));
        }
        $tstm->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $tstm = UserTestimonial::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
            if (file_exists(public_path('assets/front/img/user/testimonials/' . $tstm->image))) {
                @unlink(public_path('assets/front/img/user/testimonials/' . $tstm->image));
            }
            $tstm->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }
}
