<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;

class CheckEditorialDirector
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
        if(!empty(session()->get('currentUser')))
        {
            if(session()->get('currentUser')['role'] == 'tổng biên tập')
            {
                return $next($request);
            }
            else
            {
                return redirect()->route('admin.dashboard');
            }
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
