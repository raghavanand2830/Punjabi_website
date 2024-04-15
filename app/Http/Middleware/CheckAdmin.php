<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckAdmin
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
                return redirect('admin_login');
        }
        else{
            if(auth()->user()->role_id != 1){
                return redirect('admin_login');
            }
        }
        return $next($request);
    }
}
