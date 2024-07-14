<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AdminMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
    protected function authenticate($request, array $guards)
    {

        if ($this->auth->guard('admin')->check()) {
            return $this->auth->shouldUse('admin');
        }
        $this->unauthenticated($request, ['admin']);
    }
}
