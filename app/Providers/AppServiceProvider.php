<?php

namespace App\Providers;

use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Social;
use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\Menu;
use App\Models\User\BasicSetting;
use App\Models\User\SEO;
use App\Models\User\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function changePreferences($userId)
    {
        $currentPackage = UserPermissionHelper::currentPackagePermission($userId);

        $preference = UserPermission::where([
            ['user_id', $userId]
        ])->first();

        // if current package does not match with 'package_id' of 'user_permissions' table, then change 'package_id' in 'user_permissions'
        if (!empty($currentPackage) && ($currentPackage->id != $preference->package_id)) {
            $preference->package_id = $currentPackage->id;

            $features = !empty($currentPackage->features) ? json_decode($currentPackage->features, true) : [];
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            $preference->permissions = json_encode($features);
            $preference->package_id = $currentPackage->id;
            $preference->save();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (!app()->runningInConsole()) {
            $socials = Social::orderBy('serial_number', 'ASC')->get();
            $langs = Language::all();
    
            View::composer('*', function ($view) {
                if (session()->has('lang')) {
                    $currentLang = Language::where('code', session()->get('lang'))->first();
                } else {
                    $currentLang = Language::where('is_default', 1)->first();
                }
    
                $bs = $currentLang->basic_setting;
                $be = $currentLang->basic_extended;
    
                if (Menu::where('language_id', $currentLang->id)->count() > 0) {
                    $menus = Menu::where('language_id', $currentLang->id)->first()->menus;
                } else {
                    $menus = json_encode([]);
                }
                if ($currentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }
                $view->with('bs', $bs);
                $view->with('be', $be);
                $view->with('currentLang', $currentLang);
                $view->with('menus', $menus);
                $view->with('rtl', $rtl);
            });
    
            View::composer(['user.*'], function ($view) {
                if (Auth::guard('web')->check()) {
                    $userId = Auth::guard('web')->user()->id;
                    // change package_id in 'user_permissions' 
                    $this->changePreferences($userId);
                    if (request()->has('language')) {
                        $lang = UserLanguage::where([
                            ['code', request('language')],
                            ['user_id', $userId]
                        ])->first();
                        session()->put('currentLangCode', request('language'));
                    } else {
                        $lang = UserLanguage::where([
                            ['is_default', 1],
                            ['user_id', $userId]
                        ])->first();
                        session()->put('currentLangCode', $lang->code);
                    }
                    $keywords = json_decode($lang->keywords, true);
                    $userBs = DB::table('user_basic_settings')->where('user_id', $userId)->first();
                    $view->with('userBs', $userBs);
                    $view->with('keywords', $keywords);
                    $view->with('userDefaultLang ', $lang);
                }
            });
    
            View::composer(['user.profile.*', 'user.profile1.*', 'user.profile-common.*', 'user-front.*'], function ($view) {
                $user = getUser();
                // change package_id in 'user_permissions' 
                $this->changePreferences($user->id);
    
                if (session()->has('user_lang')) {
                    $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                        session()->put('user_lang', $userCurrentLang->code);
                    }
                } else {
                    $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                }
    
                $keywords = json_decode($userCurrentLang->keywords, true);
    
    
                $userBs = BasicSetting::where('user_id', $user->id)->first();
                $social_medias = $user->social_media()->get() ?? collect([]);
                $userSeo = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first();
                $userLangs = UserLanguage::where('user_id', $user->id)->get();
    
                $cuurentSub = UserPermissionHelper::userPackage($user->id);
    
    
                $preferences = UserPermission::where([
                    ['user_id', $user->id],
                    ['package_id', $cuurentSub->package_id]
                ])->first();
                $userPermissions = isset($preferences) ? json_decode($preferences->permissions, true) : [];
    
                $packagePermissions = UserPermissionHelper::packagePermission($user->id);
                $packagePermissions = json_decode($packagePermissions, true);
    
                $view->with('user', $user);
                $view->with('userSeo', $userSeo);
                $view->with('userBs', $userBs);
                $view->with('social_medias', $social_medias);
                $view->with('userCurrentLang', $userCurrentLang);
                $view->with('userLangs', $userLangs);
                $view->with('keywords', $keywords);
                $view->with('userPermissions', $userPermissions);
                $view->with('packagePermissions', $packagePermissions);
            });
    
            View::share('langs', $langs);
            View::share('socials', $socials);
        }
    }
}
