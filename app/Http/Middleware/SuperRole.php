<?php

namespace App\Http\Middleware;

use Closure;

class SuperRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->user_type=="super" && auth()->check()) {
            return $next($request);
        } else {
            return response()->view('errors.403');
        }
    }
}
