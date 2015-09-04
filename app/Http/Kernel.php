<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \App\Http\Middleware\ApiMiddleware::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\AfterMiddleWare::class,
        //\App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // disabling unused AUth Middleware of Laravel

        //'auth' => \App\Http\Middleware\Authenticate::class,
        //'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        //'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // Validates API_KEY
        'check' => \App\Http\Middleware\TokenCheck::class,
        // Validates User Token
        'token' => \App\Http\Middleware\TokenAuth::class,
       

        
    ];

   
}
