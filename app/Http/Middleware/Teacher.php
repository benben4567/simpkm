<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Teacher
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
        $role = Auth::user()->role;
        if ($role != 'teacher') {
          return abort(403);
        }
        return $next($request);
      } else {
        return redirect()->route('login');
      }
    }
}
