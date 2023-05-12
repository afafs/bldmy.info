<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BasicExtended;
use App\Models\User\UserCustomDomain;
use Auth;
use Illuminate\Http\Request;
use Session;

class DomainController extends Controller
{
    public function domains() {
        $rcDomain = UserCustomDomain::where('status', '<>', 2)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        $data['rcDomain'] = $rcDomain;
        return view('user.domains', $data);
    }

    public function isValidDomain($domain_name) {
        return (preg_match("/^([a-zd](-*[a-zd])*)(.([a-zd](-*[a-zd])*))*$/i", $domain_name) //valid characters check
        && preg_match("/^.{1,253}$/", $domain_name) //overall length check
        && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $domain_name) ); //length of every label
    }


    public function domainrequest(Request $request) {
        $be = BasicExtended::select('domain_request_success_message', 'cname_record_section_title')->first();

        $rules = [
            'custom_domain' => [
                'required',
                function ($attribute, $value, $fail) use ($be) {
                    // if user request the current domain
                    if (getCdomain(Auth::user()) == $value) {
                        $fail('You cannot request your current domain.');
                    }
                    // check if domain is valid
                    if (!$this->isValidDomain($value)) {
                        $fail('Domain format is not valid');
                    } 
                }
            ]
        ];

        $request->validate($rules);

        $cdomain = new UserCustomDomain;
        $cdomain->user_id = Auth::user()->id;
        $cdomain->requested_domain = $request->custom_domain;
        $cdomain->current_domain = getCdomain(Auth::user());
        $cdomain->status = 0;
        $cdomain->save();

        $request->session()->flash('domain-success', $be->domain_request_success_message);
        return back();
    }
}
