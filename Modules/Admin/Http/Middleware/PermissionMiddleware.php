<?php

namespace Modules\Admin\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\PermissionValidate;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        //$this->handlePermission();
        return $next($request);
    }

    /**
     * Set 403 error when user has no permissions to access current page
     *
     */
    protected function handlePermission()
    {
        //check if current user have permission to access current URL

        if(!PermissionValidate::haveCurrentUrlPermission())
        {
            abort(403, "You don't have permission to perform current operation");
        }
    }
}


