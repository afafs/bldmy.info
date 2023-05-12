<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BasicSettingController extends Controller
{


    public function information()
    {
        $data['data'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)
            ->first();
        return view('user.settings.information', $data);
    }

    public function updateInfo(Request $request)
    {
        $rules = [
            'base_currency_symbol' => 'required',
            'base_currency_symbol_position' => 'required',
            'base_currency_text' => 'required',
            'base_currency_text_position' => 'required',
            'base_currency_rate' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update([
            'website_title' => $request->website_title ?? '',
            'base_currency_symbol' => $request->base_currency_symbol,
            'base_currency_symbol_position' => $request->base_currency_symbol_position,
            'base_currency_text' => $request->base_currency_text,
            'base_currency_text_position' => $request->base_currency_text_position,
            'base_currency_rate' => $request->base_currency_rate,
        ]);

        $request->session()->flash('success', toastrMsg('Updated_successfully!'));

        return 'success';
    }
}
