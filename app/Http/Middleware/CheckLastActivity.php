<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckLastActivity
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
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $now = Carbon::now();

            if ($lastActivity && $now->diffInDays(Carbon::parse($lastActivity)) > 7) {
                Auth::logout();
                return redirect()->route('login')->withErrors('You have been logged out due to inactivity.');
            }

            session(['last_activity' => $now]);
        }

        return $next($request);
    }
}
