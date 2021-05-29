<?php

namespace App\Http\Middleware;

use App\Http\Helpers\MetaHelper;
use App\Models\Shift;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class SharedInertiaData
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share(array_filter([
            'user_roles' => function () use ($request) {
                if (!$request->user()) {
                    return;
                }

                $output = array_map(function ($item) {
                    return true;
                }, array_flip(
                        $request->user()->roles->pluck('name')->toArray()
                    )
                );

                return $output;
            },
            'user_permissions' => function () use ($request) {
                if (!$request->user()) {
                    return;
                }

                return array_map(function ($item) {
                    return true;
                }, array_flip(
                    $request->user()->permissions()->pluck('name')->toArray()
                ));
            },
            'current_shift' => function() use ($request) {
                if ($request->user()) {
                    return Shift::current();
                }
            }
        ]));
        return $next($request);
    }
}
