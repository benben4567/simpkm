<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Student
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
        if ($role != 'student') {
          return abort(403);
        }
        return $next($request);
      } else {
        return redirect()->route('login');
      }
    }
}
