<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class checkActiveCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('customer')->user()->status != 1) {
            Auth::guard('customer')->logout();
            Session::flash('error', 'Your account has been deactivated!');
            return redirect(route('front.index'));
        }
        return $next($request);
    }
}
