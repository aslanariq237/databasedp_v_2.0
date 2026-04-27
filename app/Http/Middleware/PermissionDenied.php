<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionDenied
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$permissions
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     *
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permissions[0]) ? $permissions[0] : $permissions;

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }
        
        throw UnauthorizedException::forPermissions($permissions);
    }

    // Tambahkan method terminate untuk handle setelah response (opsional, tapi berguna untuk custom redirect)
    public function terminate(Request $request, $response)
    {
        //
    }
}