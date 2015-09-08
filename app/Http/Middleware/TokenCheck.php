<?php

namespace App\Http\Middleware;
use Log;
use Closure;

class TokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $api_key = $request->input('api_key');
        $key =  config('custom.api_key',Null);
        
        if($api_key !== $key){
            return app('\App\Http\Controllers\LoginController')->exception_handler(array("response" => array( 'message' => trans('message.invalid_api_key')) ));
        }
        return $next($request);
    }
}
