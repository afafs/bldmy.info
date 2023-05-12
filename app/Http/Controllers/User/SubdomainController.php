<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function subdomain() {
        $userId = Auth::user()->id;
        $features = UserPermissionHelper::packagePermission($userId);
        $data['features'] = json_decode($features, true);
        return view('user.subdomain', $data);
    }
}
