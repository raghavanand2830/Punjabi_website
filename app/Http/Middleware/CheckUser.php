<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'user')
    {
     
        if (!Auth::check()) {
                return redirect('user_login');
        }
        else{
            if(auth()->user()->role_id != 2){
                return redirect('user_login');
            }
        }
        return $next($request);
    }
}
