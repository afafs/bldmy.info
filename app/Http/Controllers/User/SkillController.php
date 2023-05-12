<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
            Session::put('currentLangCode', $lang->code);
        }
        $data['skills'] = Skill::where([
            ['language_id', '=', $lang->id],
            ['user_id', '=', Auth::id()],
        ])
            ->orderBy('id', 'DESC')
            ->get();
        return view('user.skill.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'user_language_id.required' => 'The language field is required',
            'title.required' => 'The title field is required',
            'percentage.required' => 'The percentage field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $slug = make_slug($request->title);
        $rules = [
            'user_language_id' => 'required',
            'title' => 'required|max:255',
            'percentage' => 'required|numeric|digits_between:1,100',
            'color' => 'required|max:20',
            'serial_number' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $input = $request->all();
        $input['language_id'] = $request->user_language_id;
        $input['slug'] = $slug;
        $input['user_id'] = Auth::guard('web')->user()->id;

        $skill = new Skill;
        $skill->create($input);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return
     */
    public function edit(Skill $skill)
    {
        if ($skill->user_id != Auth::guard('web')->user()->id) {
            Session::flash('warning', toastrMsg('Authorization_Failed'));
            return back();
        }
        $data['skill'] = $skill;
        return view('user.skill.edit', $data);
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
        $allowedExts = array('jpg', 'png', 'jpeg');
        $slug = make_slug($request->title);

        $rules = [
            'title' => 'required|max:255',
            'percentage' => 'required|numeric|digits_between:1,100',
            'color' => 'required|max:20',
            'serial_number' => 'required|integer'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $input = $request->all();
        $skill = Skill::findOrFail($request->skill_id);
        if ($skill->user_id != Auth::user()->id) {
            return;
        }
        $input['slug'] = $slug;
        $input['user_id'] = Auth::id();
        $skill->update($input);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function delete(Request $request)
    {
        $skill = Skill::where('user_id', Auth::user()->id)->where('id', $request->skill_id)->firstOrFail();
        $skill->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $skill = Skill::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
            $skill->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }
}
