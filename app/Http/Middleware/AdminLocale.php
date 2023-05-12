<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use Illuminate\Http\Request;

class AdminLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('admin_lang')) {
            app()->setLocale(session()->get('admin_lang'));
        } else {
            $defaultLang = Language::where('is_default', 1)->first();
            if (!empty($defaultLang)) {
                app()->setLocale($defaultLang->code);
            }
        }

        return $next($request);
    }
}
