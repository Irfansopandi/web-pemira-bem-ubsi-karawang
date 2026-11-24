<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        $middleware->priority([
            \App\Http\Middleware\EnsureAdmin::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if (\Illuminate\Support\Facades\Auth::guard('student')->check()) {
                return route('login');
            }

            if ($request->is('admin*')) {
                return route('admin.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            // Jika mahasiswa mencoba mengakses URL admin apapun (termasuk yang tidak ada / typo)
            if ($request->is('admin*') && \Illuminate\Support\Facades\Auth::guard('student')->check()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akses ditolak. Mahasiswa dilarang mengakses area administrator.',
                    ], 403);
                }

                return redirect()->route('login')->withErrors([
                    'identifier' => 'Akses ditolak. Mahasiswa dilarang mengakses panel administrator.',
                ]);
            }
        });
    })->create();
