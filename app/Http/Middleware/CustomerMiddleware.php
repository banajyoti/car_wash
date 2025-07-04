<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
   public function handle(Request $request, Closure $next)
    {
        if(auth()->user() && auth()->user()->user_type == 2){
            return $next($request);
        }

        return redirect('/');
    }
}
