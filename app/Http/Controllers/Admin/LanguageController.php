<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\BasicSetting as BS;
use App\Models\BasicExtended as BE;
use Auth;
use Validator;
use Session;

class LanguageController extends Controller
{
    public function index($lang = false)
    {
        $data['languages'] = Language::all();
        return view('admin.language.index', $data);
    }
    public function userlanguageSettings($lang = false)
    {
        $data['language'] = UserLanguage::first();
        return view('admin.language.user-language-setting', $data);
    }
    public function userlanguagekeywords($lang = false)
    {
        $data['userlanguage'] = UserLanguage::first();
        $data['json'] = json_decode($data['userlanguage']->keywords, true);
        return view('admin.language.user-language-keywords', $data);
    }

    public function addKeyword(Request $request)
    {
        $rules = [
            'keyword' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        //    for  admin default file 
        $jsonData = file_get_contents(resource_path('lang/') . 'admin_default.json');
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;
        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);
        $fileLocated = resource_path('lang/') . 'admin_default.json';
        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        //    for  admin {languages} file 
        $languages = Language::all();
        foreach ($languages as $langkey => $language) {
            $jsonData = file_get_contents(resource_path('lang/') . 'admin_' . $language->code . '.json');
            $keywords = json_decode($jsonData, true);
            $datas = [];
            $datas[$request->keyword] = $request->keyword;
            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);
            $fileLocated = resource_path('lang/') . 'admin_' . $language->code . '.json';
            // put all the keywords in the selected language file
            file_put_contents($fileLocated, $jsonData);
        }

        // get all the keywords of the selected language
        // convert json encoded string into a php associative array
        Session::flash('success', __('Store successfully'));
        return 'success';
    }

    public function addFrontKeyword(Request $request)
    {
        
        $rules = [
            'front_keyword' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        //    for  admin default file 
        $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->front_keyword] = $request->front_keyword;
        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);
        $fileLocated = resource_path('lang/') . 'default.json';
        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        //    for  admin {languages} file 
        $languages = Language::all();
        foreach ($languages as $langkey => $language) {
            $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');
            $keywords = json_decode($jsonData, true);
            $datas = [];
            $datas[$request->front_keyword] = $request->front_keyword;
            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);
            $fileLocated = resource_path('lang/') . $language->code . '.json';
            // put all the keywords in the selected language file
            file_put_contents($fileLocated, $jsonData);
        }
        // get all the keywords of the selected language
        // convert json encoded string into a php associative array
        Session::flash('success', __('New Frontend Keyword Added successfully'));
        return 'success';
    }



    public function useraddKeyword(Request $request)
    {
        $rules = [
            'keyword' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $userlanguage = UserLanguage::first();
        $keywords = json_decode($userlanguage->keywords, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;
        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);
        $userlanguage->keywords = $jsonData;
        $userlanguage->save();


        Session::flash('success', __('New Keyword Added successfully'));
        return "success";
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                'unique:languages'
            ],
            'direction' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $data = file_get_contents(resource_path('lang/') . 'default.json');
        $admin_data = file_get_contents(resource_path('lang/') . 'admin_default.json');
        $json_file =  trim($request->code) . '.json';
        $admin_json_file = 'admin_' . trim($request->code) . '.json';
        $path = resource_path('lang/') . $json_file;
        $admin_path = resource_path('lang/') . $admin_json_file;

        File::put($path, $data);
        File::put($admin_path, $admin_data);

        $in['name'] = $request->name;
        $in['code'] = $request->code;
        $in['rtl'] = $request->direction;
        if (Language::where('is_default', 1)->count() > 0) {
            $in['is_default'] = 0;
        } else {
            $in['is_default'] = 1;
        }
        $lang = Language::create($in);

        // duplicate First row of basic_settings for current language
        $dbs = Language::where('is_default', 1)->first()->basic_setting;
        $cols = json_decode($dbs, true);
        $bs = new BS;
        foreach ($cols as $key => $value) {
            // if the column is 'id' [primary key] then skip it
            if ($key == 'id') {
                continue;
            }
            // create favicon image using default language image & save unique name in database
            if ($key == 'favicon') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->favicon);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->favicon, ".")) !== FALSE) {
                    $ext = substr($dbs->favicon, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;
                @copy($dimg, public_path('assets/front/img/' . $newImgName));
                // save the unique name in database
                $bs[$key] = $newImgName;
                // continue the loop
                continue;
            }
            // create logo image using default language image & save unique name in database
            if ($key == 'logo') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->logo);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->logo, ".")) !== FALSE) {
                    $ext = substr($dbs->logo, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }
            // create logo image using default language image & save unique name in database
            if ($key == 'preloader') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->preloader);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->preloader, ".")) !== FALSE) {
                    $ext = substr($dbs->preloader, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create logo image using default language image & save unique name in database
            if ($key == 'maintenance_img') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->maintenance_img);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->maintenance_img, ".")) !== FALSE) {
                    $ext = substr($dbs->maintenance_img, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create breadcrumb image using default language image & save unique name in database
            if ($key == 'breadcrumb') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->breadcrumb);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->breadcrumb, ".")) !== FALSE) {
                    $ext = substr($dbs->breadcrumb, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create footer_logo image using default language image & save unique name in database
            if ($key == 'footer_logo') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->footer_logo);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->footer_logo, ".")) !== FALSE) {
                    $ext = substr($dbs->footer_logo, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create intro_main_image image using default language image & save unique name in database
            if ($key == 'intro_main_image') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbs->intro_main_image);

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->intro_main_image, ".")) !== FALSE) {
                    $ext = substr($dbs->intro_main_image, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/' . $newImgName));

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            $bs[$key] = $value;
        }
        $bs['language_id'] = $lang->id;
        $bs->save();

        // duplicate First row of basic_extendeds for current language
        $dbe = Language::where('is_default', 1)->first()->basic_extended;
        $be = BE::firstOrFail();
        $cols = json_decode($be, true);
        $be = new BE;
        foreach ($cols as $key => $value) {
            // if the column is 'id' [primary key] then skip it
            if ($key == 'id') {
                continue;
            }
            // create hero image using default language image & save unique name in database
            if ($key == 'hero_img') {
                // take default lang image
                $dimg = public_path(url('/assets/front/img/') . '/' . $dbe->hero_img);
                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbe->hero_img, ".")) !== FALSE) {
                    $ext = substr($dbe->hero_img, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;
                @copy($dimg, public_path('assets/front/img/' . $newImgName));
                // save the unique name in database
                $be[$key] = $newImgName;
                // continue the loop
                continue;
            }
            $be[$key] = $value;
        }
        $be['language_id'] = $lang->id;
        $be->save();
        Session::flash('success', __('Store successfully!'));
        return "success";
    }
    public function edit($id)
    {
        if ($id > 0) {
            $data['language'] = Language::findOrFail($id);
        }
        $data['id'] = $id;
        return view('admin.language.edit', $data);
    }

    public function update(Request $request)
    {
        $language = Language::findOrFail($request->language_id);

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                Rule::unique('languages')->ignore($language->id),
            ],
            'direction' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->save();
        Session::flash('success', __('Updated successfully!'));
        return "success";
    }
    public function userlanguageupdate(Request $request)
    {
        $language = UserLanguage::first();
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                // Rule::unique('user_languages')->ignore($language->id),
            ],
            'direction' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $language->name = $request->name;
        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->save();

        Session::flash('success', __('Updated successfully!'));
        return "success";
    }

    public function editKeyword($id)
    {
        $isAdmin =  0;
        if ($id > 0) {
            $la = Language::findOrFail($id);
            $json = file_get_contents(resource_path('lang/') . $la->code . '.json');
            $json = json_decode($json, true);
            $list_lang = Language::all();
            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }
            return view('admin.language.edit-keyword', compact('json', 'la', 'isAdmin'));
        } elseif ($id == 0) {
            $json = file_get_contents(resource_path('lang/') . 'default.json');
            $json = json_decode($json, true);
            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }
            return view('admin.language.edit-keyword', compact('json', 'isAdmin'));
        }
    }
    public function editAdminKeyword($id)
    {

        $isAdmin =  1;
        if ($id > 0) {
            $la = Language::findOrFail($id);
            $json = file_get_contents(resource_path('lang/') . 'admin_' . $la->code . '.json');
            $json = json_decode($json, true);
            $list_lang = Language::all();
            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }
            return view('admin.language.edit-keyword', compact('json', 'la', 'isAdmin'));
        } elseif ($id == 0) {
            $json = file_get_contents(resource_path('lang/') . 'admin_' . 'default.json');
            $json = json_decode($json, true);
            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }
            return view('admin.language.edit-keyword', compact('json', 'isAdmin'));
        }
    }

    public function updateKeyword(Request $request, $id)
    {
        $lang = Language::findOrFail($id);
        $content = json_encode($request->keys);
        if ($content === 'null') {
            return back()->with('alert', __('At Least One Field Should Be Fill-up'));
        }
        if ($request->isAdmin) {
            file_put_contents(resource_path('lang/') . 'admin_' . $lang->code . '.json', $content);
        } else {
            file_put_contents(resource_path('lang/') . $lang->code . '.json', $content);
        }

        Session::flash('success', __('Updated successfully!'));
        return 'success';
     
    }

    public function updateUserKeyword(Request $request)
    {
        $lang = UserLanguage::first();

        $keywords = $request->except('_token');
        $lang->keywords = $keywords;
        $lang->save();
        return back()->with('success', __('Updated successfully!'));
    }


    public function delete($id)
    {
        $la = Language::findOrFail($id);
        if ($la->is_default == 1) {
            return back()->with('warning', 'Default language cannot be deleted!');
        }
        @unlink(public_path('assets/front/img/languages/' . $la->icon));
        @unlink(resource_path('lang/') . $la->code . '.json');
        @unlink(resource_path('lang/') . 'admin_' . $la->code . '.json');
        if (session()->get('admin_lang') == $la->code) {
            session()->forget('admin_lang');
        }

        // deleting basic_settings and basic_extended for corresponding language & unlink images
        $bs = $la->basic_setting;
        if (!empty($bs)) {

            @unlink(public_path('assets/front/img/' . $bs->favicon));

            @unlink(public_path('assets/front/img/' . $bs->logo));

            @unlink(public_path('assets/front/img/' . $bs->preloader));

            @unlink(public_path('assets/front/img/' . $bs->breadcrumb));

            @unlink(public_path('assets/front/img/' . $bs->intro_main_image));

            @unlink(public_path('assets/front/img/' . $bs->footer_logo));

            @unlink(public_path('assets/front/img/' . $bs->maintenance_img));

            $bs->delete();
        }
        $be = $la->basic_extended;
        if (!empty($be)) {
            @unlink(public_path('assets/front/img/' . $be->hero_img));
            $be->delete();
        }



        // deleting pages for corresponding language
        if (!empty($la->pages)) {
            $la->pages()->delete();
        }

        // deleting testimonials for corresponding language
        if (!empty($la->testimonials)) {
            $testimonials = $la->testimonials;
            foreach ($testimonials as $testimonial) {
                @unlink(public_path('assets/front/img/testimonials/' . $testimonial->image));
                $testimonial->delete();
            }
        }


        // deleting feature for corresponding language
        if (!empty($la->features)) {
            $features = $la->features;
            foreach ($features as $feature) {
                $feature->delete();
            }
        }


        // deleting services for corresponding language
        if (!empty($la->blogs)) {
            $blogs = $la->blogs;
            foreach ($blogs as $blog) {
                @unlink(public_path('assets/front/img/blogs/' . $blog->main_image));
                $blog->delete();
            }
        }

        // deleting blog categories for corresponding language
        if (!empty($la->bcategories)) {
            $bcategories = $la->bcategories;
            foreach ($bcategories as $bcat) {
                $bcat->delete();
            }
        }

        // deleting partners for corresponding language
        if (!empty($la->partners)) {
            $partners = $la->partners;
            foreach ($partners as $partner) {
                @unlink(public_path('assets/front/img/partners/' . $partner->image));
                $partner->delete();
            }
        }

        // deleting processes for corresponding language
        if (!empty($la->processes)) {
            $processes = $la->processes;
            foreach ($processes as $process) {
                @unlink(public_path('assets/front/img/process/' . $process->image));
                $process->delete();
            }
        }

        // deleting partners for corresponding language
        if (!empty($la->popups)) {
            $popups = $la->popups;
            foreach ($popups as $popup) {
                @unlink(public_path('assets/front/img/popups/' . $popup->background_image));
                @unlink(public_path('assets/front/img/popups/' . $popup->image));
                $popup->delete();
            }
        }

        // deleting useful links for corresponding language
        if (!empty($la->ulinks)) {
            $la->ulinks()->delete();
        }

        // deleting faqs for corresponding language
        if (!empty($la->faqs)) {
            $la->faqs()->delete();
        }

        // deleting menus for corresponding language
        if (!empty($la->menus)) {
            $la->menus()->delete();
        }

        // deleting seo for corresponding language
        if (!empty($la->seo)) {
            $la->seo->delete();
        }

        // if the the deletable language is the currently selected language in frontend then forget the selected language from session
        session()->forget('lang');

        $la->delete();
        return back()->with('success', __('Deleted successfully!'));
    }


    public function default(Request $request, $id)
    {
        Language::where('is_default', 1)->update(['is_default' => 0]);
        $lang = Language::find($id);
        $lang->is_default = 1;
        $lang->save();
        return back()->with('success', $lang->name . ' laguage is set as defualt.');
    }

    public function rtlcheck($langid)
    {
        if ($langid > 0) {
            $lang = Language::find($langid);
        } else {
            return 0;
        }

        return $lang->rtl;
    }
}
