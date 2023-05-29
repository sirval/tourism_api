<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponsesTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserACL
{
    use ApiResponsesTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $acl)
    {
        if (! auth()->check()) {
           return $this->errorResponse('Unauthorized', 401, false);
        }

        $user = auth()->user();
        if ($user->role_id == $acl) {
            return $next($request);
        }
        return $this->errorResponse('Access Forbidden', 403, false);
        
    }
}
