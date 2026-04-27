<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Tangkap UnauthorizedException dari Spatie (ketika permission/role tidak cukup)
        if ($exception instanceof UnauthorizedException) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini.'
                ], 403);
                toastError('Anda tidak memiliki izin untuk mengakses halaman ini!');
            }

            // Untuk request web biasa: redirect back + toast error menggunakan ToasterMagic
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak!',
                    'message' => 'Anda tidak memiliki izin untuk mengakses modul ini.'
                ]);
        }

        return parent::render($request, $exception);
    }
}