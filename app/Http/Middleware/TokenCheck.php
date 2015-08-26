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
            $statusCode = 500;
            $status = trans('message.rest_status_fail');
            $error['message'] = trans('message.invalid_api_key');
            return response()->json(array("status"=>$status, 'satus code'=>$statusCode, 'response'=>$error),500);
        }
        return $next($request);
    }
}
