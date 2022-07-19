<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityQuestions
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
        if (auth()->user()->is_admin == 1 || auth()->user()->is_admin == 2) {
            return redirect('/admin/dashboard');
        }
        if(auth()->user()->screen == 1) {
            return redirect(route('personal-information'));
        } elseif(auth()->user()->screen == 2) {
            return $next($request);
//            return redirect(route('security-questions'));
        } elseif(auth()->user()->screen == 5) {
            return redirect(route('banking-information'));
        } elseif(auth()->user()->screen == 3) {
            return redirect(route('add-card'));
        } elseif(auth()->user()->screen == 4) {
            return redirect(route('i-am'));
        } else {
            return redirect(route('home'));
        }
        return $next($request);
    }
}
