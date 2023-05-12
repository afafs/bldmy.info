<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\HomePageStoreRequest;
use App\Models\User\BasicSetting;
use App\Models\User\HomePageText;
use App\Models\User\Language;
use App\Models\User\SEO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Purifier;
use Validator;
use Response;

class BasicController extends Controller
{
    public function themeVersion()
    {
        $data = BasicSetting::where('user_id',Auth::id())->first();

        return view('user.settings.themes', ['data' => $data]);
    }

    public function updateThemeVersion(Request $request)
    {
        $rule = [
            'theme' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $data = BasicSetting::where('user_id',Auth::id())->first();
        $data->theme = $request->theme;
        $data->save();

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return 'success';
    }

    public function favicon(Request $request)
    {
        $data['basic_setting'] = BasicSetting::where('user_id',Auth::id())->first();
        return view('user.settings.favicon',$data);
    }

    public function updatefav(Request $request)
    {
        $img = $request->file('favicon');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'favicon' => [
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
        
        $request->validate($rules);

        if ($request->hasFile('favicon')) {
            $filename = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('assets/front/img/user/'), $filename);
            $bss = BasicSetting::where('user_id',Auth::id())->first();
            if(!is_null($bss)){
                if($bss->favicon){
                    @unlink(public_path('assets/front/img/user/' . $bss->favicon));
                }
                $bss->favicon = $filename;
                $bss->user_id = Auth::id();
                $bss->save();
            }else {
                $bs = new BasicSetting();
                $bs->favicon = $filename;
                $bs->user_id = Auth::id();
                $bs->save();
            }
        }
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }

    public function logo(Request $request)
    {
        $data['basic_setting'] = BasicSetting::where('user_id',Auth::id())->first();
        return view('user.settings.logo',$data);
    }

    public function updatelogo(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
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

        $request->validate($rules);

        if ($request->hasFile('file')) {
            $bss = BasicSetting::where('user_id',Auth::id())->first();
            $filename = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('assets/front/img/user/'), $filename);
            $bss = BasicSetting::where('user_id',Auth::id())->first();
            if(!is_null($bss)){
                if($bss->logo){
                    @unlink(public_path('assets/front/img/user/' . $bss->logo));
                }
                $bss->logo = $filename;
                $bss->user_id = Auth::id();
                $bss->save();
            }else {
                $bs = new BasicSetting();
                $bs->logo = $filename;
                $bs->user_id = Auth::id();
                $bs->save();
            }
        }
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }

    public function preloader(Request $request)
    {
        $data['basic_setting'] = BasicSetting::where('user_id',Auth::id())->first();
        return view('user.settings.preloader', $data);
    }

    public function updatepreloader(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'gif');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, gif image is allowed");
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);



        if ($request->hasFile('file')) {
            $bss = BasicSetting::where('user_id',Auth::id())->first();
            $filename = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('assets/front/img/user/'), $filename);
            if(!is_null($bss)){
                @unlink(public_path('assets/front/img/user/' . $bss->preloader));
                $bss->preloader = $filename;
                $bss->user_id = Auth::id();
                $bss->save();
            }else {
                $bs = new BasicSetting();
                $bs->preloader = $filename;
                $bs->user_id = Auth::id();
                $bs->save();
            }
        }

        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }

    public function homePageTextEdit(Request $request){
        $language = Language::where('user_id', Auth::user()->id)->where('code', $request->language)->firstOrFail();
        $text = HomePageText::where('user_id', Auth::user()->id)->where('language_id', $language->id);
        if ($text->count() == 0) {
            $text = new HomePageText;
            $text->language_id = $language->id;
            $text->user_id = Auth::user()->id;
            $text->save();
        } else {
            $text = $text->first();
        }

        $data['home_setting'] = $text;
        $data['language'] = $language;

        return view('user.home.edit',$data);
    }

    public function homePageTextUpdate(Request $request){
        $homeText = HomePageText::query()->where('language_id', $request->language_id)->where('user_id', Auth::user()->id)->firstOrFail();
        foreach ($request->types as $key => $type) {
            if ($type == 'about_image' || $type == 'skills_image' || $type == 'achievement_image' || $type == 'hero_image') {
                continue;
            }
            $homeText->$type = Purifier::clean($request[$type]);
        }
        if($request->hasFile('hero_image')){
            $heroImage = uniqid() . '.' .$request->file('hero_image')->getClientOriginalExtension();
            $request->file('hero_image')->move(public_path('assets/front/img/user/home_settings/'), $heroImage);
            @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->hero_image));
            $homeText->hero_image = $heroImage;
        }
        if($request->hasFile('about_image')){
            $aboutImage = uniqid() . '.' .$request->file('about_image')->getClientOriginalExtension();
            $request->file('about_image')->move(public_path('assets/front/img/user/home_settings/'), $aboutImage);
            @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->about_image));
            $homeText->about_image = $aboutImage;
        }
        if($request->hasFile('skills_image')){
            $technicalImage = uniqid() . '.' .$request->file('skills_image')->getClientOriginalExtension();
            $request->file('skills_image')->move(public_path('assets/front/img/user/home_settings/'), $technicalImage);
            @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->skills_image));
            $homeText->skills_image = $technicalImage;
        }
        if($request->hasFile('achievement_image')){
            $achievementImage = uniqid() . '.' .$request->file('achievement_image')->getClientOriginalExtension();
            $request->file('achievement_image')->move(public_path('assets/front/img/user/home_settings/'), $achievementImage);
            @unlink(public_path('assets/front/img/user/home_settings/' . $homeText->achievement_image));
            $homeText->achievement_image = $achievementImage;
        }
        $homeText->user_id = Auth::id();
        $homeText->language_id = $request->language_id;
        $homeText->save();
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function homeImageRemove(Request $request) {
        $userId = $request->userId;
        $type = $request->type;
        $langId = $request->langId;

        if (Auth::user()->id != $userId) {
            return;
        }

        if ($type == "hero") {
            $homeText = HomePageText::where('user_id', $userId)->where('language_id', $langId)->first();
            $homeText->hero_image = NULL;
            $homeText->save();
        }

        Session::flash('success', toastrMsg('Image_Removed'));
        return "success";
    }

    public function cvUpload(){
        $data['basic_setting'] = BasicSetting::where('user_id',Auth::id())->first();
        return view('user.cv',$data);
    }
    public function updateCV(Request $request){
        $rules = [
            'cv'  => "required|mimes:pdf|max:10000"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $file = $request->file('cv');
        if ($request->hasFile('cv')) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/front/img/user/cv/'), $filename);
            $bss = BasicSetting::where('user_id',Auth::id())->first();
            if(!is_null($bss)){
                if($bss->favicon){
                    @unlink(public_path('assets/front/img/user/cv/' . $bss->cv));
                }
                $bss->cv_original = $file->getClientOriginalName();
                $bss->cv = $filename;
                $bss->save();
            }else {
                $bs = new BasicSetting();
                $bs->cv_original = $file->getClientOriginalName();
                $bs->cv = $filename;
                $bs->user_id = Auth::id();
                $bs->save();
            }
        }
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }

    public function deleteCV() {
        $bs = BasicSetting::where('user_id',Auth::id())->first();
        @unlink(public_path('assets/front/img/user/cv/' . $bs->cv));
        $bs->cv = NULL;
        $bs->cv_original = NULL;
        $bs->save();

        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }

    public function seo(Request $request)
    {
      // first, get the language info from db
      $language = Language::where('code', $request->language)->where('user_id', Auth::user()->id)->firstOrFail();
      $langId = $language->id;

      // then, get the seo info of that language from db
      $seo = SEO::where('language_id', $langId)->where('user_id', Auth::user()->id);

      if ($seo->count() == 0) {
        // if seo info of that language does not exist then create a new one
        SEO::create($request->except('language_id', 'user_id') + [
          'language_id' => $langId,
          'user_id' => Auth::user()->id
        ]);
      }

      $information['language'] = $language;

      // then, get the seo info of that language from db
      $information['data'] = $seo->first();

      // get all the languages from db
      $information['langs'] = Language::where('user_id', Auth::user()->id)->get();

      return view('user.settings.seo', $information);
    }

    public function updateSEO(Request $request)
    {
      // first, get the language info from db
     
      $language = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
      $langId = $language->id;
      

      // then, get the seo info of that language from db
      $seo = SEO::where('language_id', $langId)->where('user_id', Auth::user()->id)->first();

      // else update the existing seo info of that language
      $seo->update($request->all());

      $request->session()->flash('success', toastrMsg('Updated_successfully!'));

      return redirect()->back();
    }


    public function plugins()
    {
        $data = BasicSetting::where('user_id', Auth::guard('web')->user()->id)
            ->select('whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'analytics_status', 'measurement_id', 'disqus_status', 'disqus_short_name', 'pixel_status', 'pixel_id', 'tawkto_status', 'tawkto_direct_chat_link')
            ->first();
        return view('user.settings.plugins', compact('data'));
    }

    public function updateAnalytics(Request $request)
    {
        $rules = [
            'analytics_status' => 'required',
            'measurement_id' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'analytics_status' => $request->analytics_status,
                'measurement_id' => $request->measurement_id
            ]
        );

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return back();
    }

    public function updateWhatsApp(Request $request)
    {
        $rules = [
            'whatsapp_status' => 'required',
            'whatsapp_number' => 'required',
            'whatsapp_header_title' => 'required',
            'whatsapp_popup_status' => 'required',
            'whatsapp_popup_message' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'whatsapp_status' => $request->whatsapp_status,
                'whatsapp_number' => $request->whatsapp_number,
                'whatsapp_header_title' => $request->whatsapp_header_title,
                'whatsapp_popup_status' => $request->whatsapp_popup_status,
                'whatsapp_popup_message' => clean($request->whatsapp_popup_message)
            ]
        );

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return back();
    }

    public function updateDisqus(Request $request)
    {
        $rules = [
            'disqus_status' => 'required',
            'disqus_short_name' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'disqus_status' => $request->disqus_status,
                'disqus_short_name' => $request->disqus_short_name
            ]
        );

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return back();
    }

    public function updatePixel(Request $request)
    {
        $rules = [
            'pixel_status' => 'required',
            'pixel_id' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'pixel_status' => $request->pixel_status,
                'pixel_id' => $request->pixel_id
            ]
        );

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return back();
    }

    public function updateTawkto(Request $request)
    {
        $rules = [
            'tawkto_status' => 'required',
            'tawkto_direct_chat_link' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'tawkto_status' => $request->tawkto_status,
                'tawkto_direct_chat_link' => $request->tawkto_direct_chat_link
            ]
        );

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return back();
    }
}
