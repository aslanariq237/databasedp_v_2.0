<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PermissionDenied;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {        
        $exceptions->render(function (UnauthorizedException $e, $request) {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini.'
            ], 403);
        }

        return redirect()->back()->with('swal', [
            'title' => 'Akses Ditolak!',
            'text'  => 'Anda tidak memiliki permission untuk mengakses modul ini.',
            'icon'  => 'error',  // error, success, warning, info, question
            'timer' => 3000,     // otomatis hilang setelah 5 detik (opsional)
            'timerProgressBar' => true,
        ]);
    });
        
    })->create();
