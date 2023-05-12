<?php

namespace App\Http\Controllers\User;


use Validator;
use Illuminate\Http\Request;
use App\Models\User\Category;
use App\Models\User\Language;
use App\Models\User\FormInput;
use App\Models\User\QuoteInput;
use App\Models\User\SubCategory;
use App\Http\Controllers\Controller;
use App\Models\User\CategoryContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('setlang');
    }
    // Start category CRUD 
    public function category()
    {
        $language = Language::where('code', request('language'))->where('user_id', Auth::guard('web')->user()->id)->first();
        $data['language'] = $language;
        $data['languages'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $data['categories'] = Category::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $language->id)->get();
        return view('user.appointment.category', $data);
    }
    public function categoryStore(Request $request)
    {
        if ($request->table_id) {
            $rules = [
                'name' => 'required',
            ];
        } else {
            $rules = [
                'image' => 'mimes:jpeg,jpg,png,svg,gif',
                'name' => 'required',
                'price' => 'required',
                'user_language_id' => 'required'
            ];
        }
        $messages = [
            'image.mimes' => 'Only JPG, PNG, JPEG, SVG, GIF Images are allowed',
            'name.required' => 'The Name field is required',
            'price.required' => 'The Fee field is required',
            'user_language_id.required' => 'The Language field is required',
        ];

        $request->validate($rules, $messages);
        if ($request->table_id) {
            $category = Category::find($request->table_id);
            if ($request->hasFile('image')) {
                $rules = [
                    'image' => 'mimes:jpeg,jpg,png,svg,gif'
                ];
                $messages = [
                    'image.mimes' => 'Only JPG, PNG, JPEG, SVG, GIF Images are allowed',
                ];
                $request->validate($rules, $messages);
                // first, delete the previous image from local storage
                @unlink(public_path('assets/user/img/category/' . $category->image));
                // get image extension
                $imageURL = $request->image;
                $fileExtension = $imageURL->extension();
                // set a name for the image and store it to local storage
                $imageName = time() . '.' . $fileExtension;
                $directory = public_path('assets/user/img/category/');
                @mkdir($directory, 0775, true);
                @copy($imageURL, $directory . $imageName);
            }
            // update existing entry
            $category->name = $request->name;
            $category->appointment_price = $request->price;
            if ($request->hasFile('image')) {
                $category->image = $request->hasFile('image') ? $imageName : null;
            }
            $category->save();
            $action = 'updated';
        } else {
            if ($request->hasFile('image')) {
                // get image extension
                $imageURL = $request->image;
                $fileExtension = $imageURL->extension();
                // set a name for the image and store it to local storage
                $imageName = time() . '.' . $fileExtension;
                $directory = public_path('assets/user/img/category/');
                @mkdir($directory, 0775, true);
                @copy($imageURL, $directory . $imageName);
            }
            // create new entry
            $category = Category::create([
                'user_id' => Auth::guard('web')->user()->id,
                'language_id' => $request->user_language_id,
                'name' => $request->name,
                'appointment_price' => $request->price,
                'image' => $request->hasFile('image') ? $imageName : null
            ]);
            $action = 'created';
        }
        $request->session()->flash('success',  toastrMsg('Store_successfully!'));
        return back();
    }
    public function categoryDelete(Category $category)
    {
        $check = Category::findOrFail($category->id);
        if ($check) {
            @unlink(public_path('assets/user/img/category/' . $category->image));
            category::find($category->id)->delete();
            $category->delete();
        }                                           
        request()->session()->flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function categoryBulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $category = Category::findOrFail($id);
            @unlink(public_path('assets/user/img/category/' . $category->image));
            $category->delete();
        }
        $request->session()->flash('success', toastrMsg('Deleted_successfully!'));
        return 'success';
    }
    // End category CRUD 

    // start form builder 
    public function form($id = null)
    {

        $lang = Language::where([['code', request('language')], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        if ($id == null) {
            $data['back_url'] = null; // route('user.forminput') . '?language=' . $lang->code;
            $data['inputs'] = FormInput::where([['language_id', $lang->id], ['user_id', Auth::guard('web')->user()->id], ['category_id', null]])->orderBy('order_number', 'ASC')->get();
        } else {
            $data['back_url'] = route('user.appointment.category') . '?language=' . $lang->code;
            $data['inputs'] = FormInput::where([['language_id', $lang->id], ['user_id', Auth::guard('web')->user()->id], ['category_id', $id]])->orderBy('order_number', 'ASC')->get();
        }
        $data['type_details'] = Category::where('id', $id)->first();


        return view('user.appointment.form', $data);
    }
    // end form builder 
}
