<?php

namespace App\Http\Controllers\User;

use Auth;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Models\User\UserVcard;
use App\Http\Controllers\Controller;
use App\Models\User\UserVcardProject;
use App\Models\User\UserVcardService;
use App\Http\Helpers\LimitCheckerHelper;
use App\Models\User\UserVcardTestimonial;

class VcardController extends Controller
{
    public function vcard()
    {
        $data['vcards'] = UserVcard::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('user.vcard.index', $data);
    }

    public function create()
    {
        return view('user.vcard.create');
    }

    public function edit(UserVcard $uservcard)
    {
        if ($uservcard->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }
        $data['vcard'] = $uservcard;
        return view('user.vcard.edit', $data);
    }

    public function services(UserVcard $uservcard)
    {
        if ($uservcard->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }
        $vcard = $uservcard;
        $services = $vcard->user_vcard_services()->orderBy('id', 'DESC')->get();

        $data['vcard'] = $vcard;
        $data['services'] = $services;

        return view('user.vcard.services.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function serviceStore(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $messages = [
            'title.required' => 'The title field is required',
            'serial_number.required' => 'The serial number field is required',
            'image.required' => 'The image field is required',
        ];


        $rules = [
            'user_vcard_id' => 'required',
            'title' => 'required|max:255',
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

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $directory = public_path('assets/front/img/user/services/');
            if (!file_exists($directory)) mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $filename);
            $input['image'] = $filename;
        }
        $input['user_vcard_id'] = $request->user_vcard_id;
        $service = new UserVcardService();
        $service->create($input);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function serviceUpdate(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $messages = [
            'title.required' => 'The title field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $rules = [
            'title' => 'required|max:255',
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

        $service = UserVcardService::findOrFail($request->service_id);
        if ($service->user_vcard->user_id != Auth::user()->id) {
            return;
        }
        $input = $request->all();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('image')->move(public_path('assets/front/img/user/services/'), $filename);
            if (file_exists(public_path('assets/front/img/user/services/' . $service->image))) {
                @unlink(public_path('assets/front/img/user/services/' . $service->image));
            }

            $input['image'] = $filename;
        }
        $service->update($input);

        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function serviceDelete(Request $request)
    {
        $service = UserVcardService::findOrFail($request->service_id);
        @unlink(public_path('assets/front/img/user/services/' . $service->image));
        $service->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkServiceDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $service = UserVcardService::findOrFail($id);
            @unlink(public_path('assets/front/img/user/services/' . $service->image));
            $service->delete();
        }
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return "success";
    }

    public function store(Request $request)
    {


        $user  = Auth::guard('web')->user();
        $vcard_limit =  LimitCheckerHelper::vcardLimitchecker($user->id);
        $vcards = UserVcard::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        if ($vcards->count() >= $vcard_limit) {
            Session::flash('warning', toastrMsg('maximum_limit_exceeded'));
            return "success";
        }

        $profileImg = $request->file('profile_image');
        $coverImg = $request->file('cover_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'vcard_name' => 'required|max:255',
            'template' => 'required',
            'direction' => 'required',
            'name' => 'nullable|max:255',
            'occupation' => 'nullable|max:255',
            'profile_image' => [
                'required',
                function ($attribute, $value, $fail) use ($profileImg, $allowedExts) {
                    if (!empty($profileImg)) {
                        $ext = $profileImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                        $size = $profileImg->getSize();
                        if ($size > 200000) {
                            return $fail("Image size cannot be greater than 200 KB");
                        }
                    }
                },
            ],
            'cover_image' => [
                function ($attribute, $value, $fail) use ($coverImg, $allowedExts) {
                    if (!empty($coverImg)) {
                        $ext = $coverImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
            'icons.*' => 'required',
            'colors.*' => 'required',
            'labels.*' => 'required',
            'values.*' => 'required',
        ];

        $messages = [
            'icons.*.required' => 'The Icon field cannot be empty',
            'colors.*.required' => 'The Color field cannot be empty',
            'labels.*.required' => 'The Label field cannot be empty',
            'values.*.required' => 'The Value field cannot be empty'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $vcard = new UserVcard();
        $vcard->user_id = Auth::user()->id;
        $vcard->vcard_name = $request->vcard_name;
        $vcard->direction = $request->direction;
        $vcard->template = $request->template;
        if ($request->hasFile('profile_image')) {
            $filename = uniqid() . '.' . $profileImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/vcard/');
            @mkdir($dir, 0775, true);
            $request->file('profile_image')->move($dir, $filename);
            $vcard->profile_image = $filename;
        }
        if ($request->hasFile('cover_image')) {
            $filename = uniqid() . '.' . $coverImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/vcard/');
            @mkdir($dir, 0775, true);
            $request->file('cover_image')->move($dir, $filename);
            $vcard->cover_image = $filename;
        }
        $vcard->name = $request->name;
        $vcard->occupation = $request->occupation;
        $vcard->company = $request->company;
        $vcard->email = $request->email;
        $vcard->phone = $request->phone;
        $vcard->address = $request->address;
        $vcard->website_url = $request->website_url;
        $vcard->introduction = $request->introduction;

        // language keywords
        $data = file_get_contents(resource_path('lang/') . 'vcard.json');
        $vcard->keywords = $data;

        $vcard->preferences = '["Call","Whatsapp","Mail","Add to Contact","Share vCard","Information","About Us","Video","Services","Projects","Testimonials","Enquiry Form"]';

        $infoArr = [];
        $labels = $request->labels ? $request->labels : [];
        $values = $request->values ? $request->values : [];
        $icons = $request->icons ? $request->icons : [];
        $colors = $request->colors ? $request->colors : [];
        $links = $request->links ? $request->links : [];

        foreach ($labels as $key => $label) {
            $info = [
                'icon' => $icons["$key"],
                'color' => $colors["$key"],
                'label' => $labels["$key"],
                'link' => in_array($key, $links) ? 1 : 0,
                'value' => $values["$key"]
            ];
            $infoArr[] = $info;
        }

        $vcard->information = json_encode($infoArr);

        $vcard->save();

        $request->session()->flash('success', toastrMsg('Store_successfully!'));
        return 'success';
    }

    public function update(Request $request)
    {

        $user  = Auth::guard('web')->user();
        $vcard_limit =  LimitCheckerHelper::vcardLimitchecker($user->id);
        $vcards = UserVcard::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        if ($vcards->count() > $vcard_limit) {
            Session::flash('warning', toastrMsg('maximum_limit_exceeded'));
            return "success";
        }


        $profileImg = $request->file('profile_image');
        $coverImg = $request->file('cover_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'vcard_name' => 'required|max:255',
            'template' => 'required',
            'direction' => 'required',
            'name' => 'nullable|max:255',
            'occupation' => 'nullable|max:255',
            'profile_image' => [
                function ($attribute, $value, $fail) use ($profileImg, $allowedExts) {
                    if (!empty($profileImg)) {
                        $ext = $profileImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                        $size = $profileImg->getSize();
                        if ($size > 200000) {
                            return $fail("Image size cannot be greater than 200 KB");
                        }
                    }
                },
            ],
            'cover_image' => [
                function ($attribute, $value, $fail) use ($coverImg, $allowedExts) {
                    if (!empty($coverImg)) {
                        $ext = $coverImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
            'icons.*' => 'required',
            'colors.*' => 'required',
            'labels.*' => 'required',
            'values.*' => 'required',
        ];

        $messages = [
            'icons.*.required' => 'The Icon field cannot be empty',
            'colors.*.required' => 'The Color field cannot be empty',
            'labels.*.required' => 'The Label field cannot be empty',
            'values.*.required' => 'The Value field cannot be empty'
        ];


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $vcard = UserVcard::find($request->vcard_id);

        if ($vcard->user_id != Auth::user()->id) {
            return;
        }

        $vcard->user_id = Auth::user()->id;
        $vcard->vcard_name = $request->vcard_name;
        $vcard->direction = $request->direction;
        $vcard->template = $request->template;
        if ($request->hasFile('profile_image')) {
            $filename = uniqid() . '.' . $profileImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/vcard/');
            @mkdir($dir, 0775, true);
            $request->file('profile_image')->move($dir, $filename);
            $vcard->profile_image = $filename;
        }
        if ($request->hasFile('cover_image')) {
            $filename = uniqid() . '.' . $coverImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/user/vcard/');
            @mkdir($dir, 0775, true);
            $request->file('cover_image')->move($dir, $filename);
            $vcard->cover_image = $filename;
        }
        $vcard->name = $request->name;
        $vcard->occupation = $request->occupation;
        $vcard->company = $request->company;
        $vcard->email = $request->email;
        $vcard->phone = $request->phone;
        $vcard->address = $request->address;
        $vcard->website_url = $request->website_url;
        $vcard->introduction = $request->introduction;

        $infoArr = [];
        $labels = $request->labels ? $request->labels : [];
        $values = $request->values ? $request->values : [];
        $icons = $request->icons ? $request->icons : [];
        $colors = $request->colors ? $request->colors : [];
        $links = $request->links ? $request->links : [];

        foreach ($labels as $key => $label) {
            $info = [
                'icon' => $icons["$key"],
                'color' => $colors["$key"],
                'label' => $labels["$key"],
                'link' => in_array($key, $links) ? 1 : 0,
                'value' => $values["$key"]
            ];
            $infoArr[] = $info;
        }

        $vcard->information = json_encode($infoArr);

        $vcard->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully'));
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
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $request->vcard_id)->firstOrFail();
        @unlink(public_path('assets/front/img/user/vcard/' . $vcard->profile_image));
        @unlink(public_path('assets/front/img/user/vcard/' . $vcard->cover_image));
        $vcard->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
            @unlink(public_path('assets/front/img/user/vcard/' . $vcard->profile_image));
            @unlink(public_path('assets/front/img/user/vcard/' . $vcard->cover_image));
            $vcard->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }

    public function information($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();;
        $information = json_decode($vcard->information, true);
        return response()->json($information);
    }

    public function projects($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();;
        $projects = $vcard->user_vcard_projects()->orderBy('id', 'DESC')->get();

        $data['vcard'] = $vcard;
        $data['projects'] = $projects;

        return view('user.vcard.projects.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function projectStore(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $messages = [
            'title.required' => 'The title field is required',
            'serial_number.required' => 'The serial number field is required',
            'image.required' => 'The image field is required',
        ];


        $rules = [
            'user_vcard_id' => 'required',
            'title' => 'required|max:255',
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

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $directory = public_path('assets/front/img/user/projects/');
            if (!file_exists($directory)) mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $filename);
            $input['image'] = $filename;
        }
        $input['user_vcard_id'] = $request->user_vcard_id;
        $project = new UserVcardProject();
        $project->create($input);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function projectUpdate(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $messages = [
            'title.required' => 'The title field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $rules = [
            'title' => 'required|max:255',
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

        $project = UserVcardProject::findOrFail($request->project_id);
        if ($project->user_vcard->user_id != Auth::user()->id) {
            return;
        }
        $input = $request->all();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('image')->move(public_path('assets/front/img/user/projects/'), $filename);
            if (file_exists(public_path('assets/front/img/user/projects/' . $project->image))) {
                @unlink(public_path('assets/front/img/user/projects/' . $project->image));
            }

            $input['image'] = $filename;
        }
        $project->update($input);

        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function projectDelete(Request $request)
    {
        $project = UserVcardProject::findOrFail($request->project_id);
        @unlink(public_path('assets/front/img/user/projects/' . $project->image));
        $project->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkProjectDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $project = UserVcardProject::findOrFail($id);
            @unlink(public_path('assets/front/img/user/projects/' . $project->image));
            $project->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }

    public function testimonials($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
        $testimonials = $vcard->user_vcard_testimonials()->orderBy('id', 'DESC')->get();

        $data['vcard'] = $vcard;
        $data['testimonials'] = $testimonials;

        return view('user.vcard.testimonials.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function testimonialStore(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $messages = [
            'name.required' => 'The name field is required',
            'serial_number.required' => 'The serial number field is required',
            'image.required' => 'The image field is required',
        ];


        $rules = [
            'user_vcard_id' => 'required',
            'name' => 'required|max:255',
            'serial_number' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
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

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $directory = public_path('assets/front/img/user/testimonials/');
            if (!file_exists($directory)) mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $filename);
            $input['image'] = $filename;
        }
        $input['user_vcard_id'] = $request->user_vcard_id;
        $testimonial = new UserVcardTestimonial();
        $testimonial->create($input);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function testimonialUpdate(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'name' => 'required|max:255',
            'serial_number' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable',
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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $testimonial = UserVcardTestimonial::findOrFail($request->testimonial_id);
        if ($testimonial->user_vcard->user_id != Auth::user()->id) {
            return;
        }
        $input = $request->all();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('image')->move(public_path('assets/front/img/user/testimonials/'), $filename);
            if (file_exists(public_path('assets/front/img/user/testimonials/' . $testimonial->image))) {
                @unlink(public_path('assets/front/img/user/testimonials/' . $testimonial->image));
            }

            $input['image'] = $filename;
        }
        $testimonial->update($input);

        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function testimonialDelete(Request $request)
    {
        $testimonial = UserVcardTestimonial::findOrFail($request->testimonial_id);
        @unlink(public_path('assets/front/img/user/testimonials/' . $testimonial->image));
        $testimonial->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function bulkTestimonialDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $testimonial = UserVcardTestimonial::findOrFail($id);
            @unlink(public_path('assets/front/img/user/testimonials/' . $testimonial->image));
            $testimonial->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }

    public function about($id)
    {
        $data['vcard'] = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
        return view('user.vcard.about', $data);
    }

    public function aboutUpdate(Request $request)
    {

        $vcard = UserVcard::find($request->vcard_id);
        if ($vcard->user_id != Auth::user()->id) {
            return;
        }
        $vcard->video = $request->video;
        $vcard->about = clean($request->about);
        $vcard->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }

    public function preferences($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();

        $preferences = [];
        if (!empty($vcard->preferences)) {
            $preferences = json_decode($vcard->preferences, true);
        }

        $data['vcard'] = $vcard;
        $data['preferences'] = $preferences;

        return view('user.vcard.preferences', $data);
    }

    public function prefUpdate(Request $request, $vcId)
    {
        $vcard = UserVcard::findOrFail($vcId);

        if ($vcard->user_id != Auth::user()->id) {
            return;
        }

        $preferences = $request->preferences;
        if (empty($preferences)) {
            $vcard->preferences = NULL;
        } else {
            $vcard->preferences = json_encode($preferences);
        }
        $vcard->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }

    public function color($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
        $data['vcard'] = $vcard;

        return view('user.vcard.color', $data);
    }

    public function colorUpdate(Request $request, $vcId)
    {
        $vcard = UserVcard::findOrFail($vcId);

        if ($vcard->user_id != Auth::user()->id) {
            return;
        }

        $vcard->base_color = $request->base_color;

        if ($vcard->template == 6) {
            $vcard->summary_background_color = $request->summary_background_color;
        }

        if ($vcard->template != 5 && $vcard->template != 6 && $vcard->template != 9 && $vcard->template != 10) {
            $vcard->call_button_color = $request->call_button_color;
            $vcard->whatsapp_button_color = $request->whatsapp_button_color;
            $vcard->mail_button_color = $request->mail_button_color;
            $vcard->add_to_contact_button_color = $request->add_to_contact_button_color;
            $vcard->share_vcard_button_color = $request->share_vcard_button_color;
            $vcard->phone_icon_color = $request->phone_icon_color;
            $vcard->email_icon_color = $request->email_icon_color;
            $vcard->address_icon_color = $request->address_icon_color;
            $vcard->website_url_icon_color = $request->website_url_icon_color;
        }

        $vcard->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }

    public function keywords($id)
    {
        $vcard = UserVcard::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
        $keywords = [];
        if (!empty($vcard->keywords)) {
            $keywords = json_decode($vcard->keywords, true);
        }

        $data['vcard'] = $vcard;
        $data['keywords'] = $keywords;

        return view('user.vcard.keywords', $data);
    }

    public function keywordsUpdate(Request $request, $vcId)
    {
        $keywords = $request->except("_token");
        if (empty($keywords)) {
            $keywords = [];
        }

        $upKeywords = [];
        foreach ($keywords as $key => $value) {
            $upKeywords["$key"] = $value;
        }
        $upKeywords = json_encode($upKeywords);

        $vcard = UserVcard::findOrFail($vcId);
        if ($vcard->user_id != Auth::user()->id) {
            return;
        }
        $vcard->keywords = $upKeywords;
        $vcard->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return 'success';
    }
}
