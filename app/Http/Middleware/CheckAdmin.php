<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()    ==  null) {
          return redirect()->route('home');
        }

        if ($request->user()->role  !=  'admin') {
            $message = "You are Not Authorized to access this Page..!";
            session()->flash('error', $message);
          return redirect()->route('account.profile');
        }
        return $next($request);
    }
}
