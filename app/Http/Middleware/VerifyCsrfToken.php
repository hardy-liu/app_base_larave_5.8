<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        //如果是swagger文档api调用的不验证csrf
        $swaggerApiUri = config('l5-swagger.routes.api');
        if ($swaggerApiUri && preg_match('/'. str_replace('/', '\/', $swaggerApiUri) . '$/', $request->header('Referer'))) {
            return true;
        }

        //如果是本地测试环境，不检查csrf
        if (env('APP_ENV') === 'local') {
            return true;
        }

        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
