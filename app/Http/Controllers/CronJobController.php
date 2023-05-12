<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserPermissionHelper;
use App\Jobs\SubscriptionExpiredMail;
use App\Jobs\SubscriptionReminderMail;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function expired() {
        $bs = BasicSetting::first();
        $be = BasicExtended::first();


        $exMembers = Membership::whereDate('expire_date', Carbon::now()->subDays(1))->where('modified', '<>', 1)->get();
        foreach ($exMembers as $key => $exMember) {
            if (!empty($exMember->user)) {
                $user = $exMember->user;
                $currPackage = UserPermissionHelper::userPackage($user->id);

                if (is_null($currPackage)) {
                    SubscriptionExpiredMail::dispatch($user, $bs, $be);
                }
            }
        }


        $rmdMembers = Membership::whereDate('expire_date', Carbon::now()->addDays($be->expiration_reminder))->get();
        foreach ($rmdMembers as $key => $rmdMember) {
            if (!empty($rmdMember->user)) {
                $user = $rmdMember->user;
                $nextPackageCount = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '>', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->count();

                if ($nextPackageCount == 0) {
                    SubscriptionReminderMail::dispatch($user, $bs, $be, $rmdMember->expire_date);
                }
            }
        }

        \Artisan::call("queue:work --stop-when-empty");
    }
}
