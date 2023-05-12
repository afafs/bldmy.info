<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserCv;
use App\Models\User\UserCvSection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class CvSectionController extends Controller
{
    public function index(UserCv $cv)
    {
        if ($cv->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', 'Authorization Failed');
            return back();
        }
        $sections = UserCvSection::where('user_cv_id', $cv->id)->orderBy('order', 'ASC')->get();
        $data['sections'] = $sections;
        $data['cv'] = $cv;
        return view('user.cv.sections', $data);
    }

    public function edit($id)
    {
        $data['section'] = UserCvSection::findOrFail($id);
        if ($data['section']->user_cv->user_id != Auth::user()->id) {
            return view('errors.404');
        }
        return view('user.cv.section-edit', $data);
    }

    public function store(Request $request)
    {
        $cv = UserCv::find($request->cv_id);

        $rules = [
            'icon' => 'required',
            'name' => 'required|max:255'
        ];
        if ($cv->template == 1) {
            $rules['column'] = 'required';
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $cvSections = UserCvSection::where('user_cv_id', $request->cv_id);
        if ($cvSections->count() > 0) {
            $maxOrder = $cvSections->max('order');
        } else {
            $maxOrder = 0;
        }
        $section = new UserCvSection();
        $section->user_cv_id = $request->cv_id;
        $section->name = clean($request->name);
        $section->icon = $request->icon;
        if ($cv->template == 1) {
            $section->column = $request->column;
        }
        $section->order = $maxOrder + 1;
        $section->save();

        $request->session()->flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'icon' => 'required',
            'name' => 'required|max:255'
        ];

        $section = UserCvSection::find($request->section_id);
        if ($section->user_cv->user_id != Auth::user()->id) {
            return;
        }
        if ($section->user_cv->template == 1) {
            $rules['column'] = 'required';
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }


        $section->name = clean($request->name);
        $section->icon = $request->icon;
        if ($section->user_cv->template == 1) {
            $section->column = $request->column;
        }
        $section->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }


    public function orderUpdate(Request $request)
    {
        $ids = $request->ids;
        $orders = $request->orders;

        if (!empty($ids)) {
            foreach ($request->ids as $key => $id) {
                $input = UserCvSection::findOrFail($id);
                if ($input->user_cv->user_id != Auth::user()->id) {
                    return;
                }
                $input->order = $orders["$key"];
                $input->save();
            }
        }
    }

    public function content($id)
    {
        $data['section'] = UserCvSection::findOrFail($id);
        if ($data['section']->user_cv->user_id != Auth::user()->id) {
            return view('errors.404');
        }
        return view('user.cv.section-content', $data);
    }

    public function getcontents($id)
    {
        $section = UserCvSection::find($id);
        $content = json_decode($section->content, true);
        return response()->json($content);
    }

    public function updateContent(Request $request)
    {

        $rules = [
            'left_borders.*' => 'required',
        ];

        $messages = [
            'left_borders.*.required' => 'The Icon field cannot be empty',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $section = UserCvSection::find($request->section_id);
        if ($section->user_cv->user_id != Auth::user()->id) {
            return;
        }

        $contentArr = [];
        $titles = $request->titles ? $request->titles : [];
        $left_borders = $request->left_borders ? $request->left_borders : [];
        $durations = $request->durations ? $request->durations : [];
        $subtitles = $request->subtitles ? $request->subtitles : [];
        $descriptions = $request->descriptions ? clean($request->descriptions) : [];
        $newLines = $request->newLines ? clean($request->newLines) : [];

        foreach ($titles as $key => $value) {
            $content = [
                'left_border' => $left_borders["$key"],
                'duration' => clean($durations["$key"]),
                'title' => clean($titles["$key"]),
                'subtitle' => clean($subtitles["$key"]),
                'description' => clean($descriptions["$key"]),
                'newLines' => $newLines["$key"]
            ];
            $contentArr[] = $content;
        }

        $section->content = json_encode($contentArr);

        $section->save();

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
        $section = UserCvSection::findOrFail($request->section_id);
        if ($section->user_cv->user_id != Auth::user()->id) {
            return back();
        }
        $section->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
}
