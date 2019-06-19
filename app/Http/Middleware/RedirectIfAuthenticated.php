<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    protected $adminHomePath = '/';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $webDomain = config('app.web_domain');

        if (Auth::guard($guard)->check()) {
            if ($request->getHost() === $webDomain) {
                return redirect('/');
            } else {
                return redirect($this->adminHomePath);
            }
        }

        return $next($request);
    }
}
