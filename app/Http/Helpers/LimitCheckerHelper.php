<?php

namespace App\Http\Helpers;

use App\Models\Membership;
use App\Models\Package;
use Carbon\Carbon;

class LimitCheckerHelper
{
    public static function vcardLimitchecker(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->number_of_vcards;
    }
}
