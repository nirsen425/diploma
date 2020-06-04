<?php

namespace App\Http\Middleware;

use Closure;

class CheckStudentStatus
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
        if ($user->student()->first()->status) {
            return $next($request);
        }

        return redirect()->route('student_cabinet_index');
    }
}
