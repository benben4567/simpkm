<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
      if (Auth::user()) {
        $role = Auth::user()->roles->pluck('name')[0];
        if ($role != 'admin') {
          return abort(403);
        }
        return $next($request);
      } else {
        return redirect()->route('login');
      }
    }
}
