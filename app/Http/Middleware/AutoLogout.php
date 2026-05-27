<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = 300; // 5 minutes

        if (session('last_activity') &&
            time() - session('last_activity') > $timeout) {

            auth()->logout();
            session()->flush();

            return redirect('/login')
                ->with('error', 'Session expired due to inactivity');
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}