<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Shift
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!\App\Models\Shift::current())
            return redirect()->route('shifts.create');
        return $next($request);
    }
}
