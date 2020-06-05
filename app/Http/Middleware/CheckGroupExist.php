<?php

namespace App\Http\Middleware;

use Closure;

class CheckGroupExist
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
        $user = $request->user();
        if ($user->student()->first()->group()->first()) {
            return $next($request);
        }

        return redirect()->route('empty_group');
    }
}
