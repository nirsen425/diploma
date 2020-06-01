<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;

class CheckStudent
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
        if (Helper::isStudent($user)) {
            return $next($request);
        }

        return redirect()->route('teacher_cabinet_index');
    }
}
