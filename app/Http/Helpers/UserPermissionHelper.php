<?php

namespace App\Http\Helpers;

use App\Models\Membership;
use App\Models\Package;
use Carbon\Carbon;
use Exception;
use phpDocumentor\Reflection\Types\Self_;

class UserPermissionHelper{

    public static function packagePermission(int $userId){
        $currentPackage = Membership::query()->where([
            ['user_id', '=', $userId],
            ['status','=',1],
            ['start_date','<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
        $package = isset($currentPackage) ? Package::query()->find($currentPackage->package_id): null;
        return $package ? $package->features : collect([]);
    }

    public static function uniqidReal($lenght = 13) {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public static function currentPackagePermission(int $userId){
        $currentPackage = Membership::query()->where([
            ['user_id', '=', $userId],
            ['status','=',1],
            ['start_date','<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
        return isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id):null;
    }

    public static function currPackageOrPending($userId) {
        $currentPackage = Self::currentPackagePermission($userId);
        if (!$currentPackage) {
            $currentPackage = Membership::query()->where([
                ['user_id', '=', $userId],
                ['status',0]
            ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            $currentPackage = isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id):null;
        }
        return isset($currentPackage) ? $currentPackage:null;
    }

    public static function currMembOrPending($userId) {
        $currMemb = Self::userPackage($userId);
        if (!$currMemb) {
            $currMemb = Membership::query()->where([
                ['user_id', '=', $userId],
                ['status',0],
            ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
        }
        return isset($currMemb) ? $currMemb : null;
    }

    public static function hasPendingMembership($userId) {
        $count = Membership::query()->where([
            ['user_id', '=', $userId],
            ['status',0]
        ])->whereYear('start_date', '<>', '9999')->count();
        return $count > 0 ? true : false;
    }

    public static function nextPackage(int $userId){
        $currMemb = Membership::query()->where([
            ['user_id', $userId],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
        $nextPackage = null;
        if($currMemb->first()) {
            $countCurrMem = $currMemb->count();
            if ($countCurrMem > 1) {
                $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
            } else {
                $nextMemb = Membership::query()->where([
                    ['user_id', $userId],
                    ['start_date', '>', $currMemb->first()->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $nextPackage = $nextMemb ? Package::query()->where('id', $nextMemb->package_id)->first() : null;
        }
        return $nextPackage;
    }

    public static function userPackage(int $userId){
        return Membership::query()->where([
            ['user_id', '=', $userId],
            ['status','=',1],
            ['start_date','<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
    }

    public static function nextMembership(int $userId){
        $currMemb = Membership::query()->where([
            ['user_id', $userId],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
        $nextMemb = null;
        if($currMemb->first()){
            $countCurrMem = $currMemb->count();
            if ($countCurrMem > 1) {
                $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
            } else {
                $nextMemb = Membership::query()->where([
                    ['user_id', $userId],
                    ['start_date', '>', $currMemb->first()->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
        }
        return $nextMemb;
    }

}
