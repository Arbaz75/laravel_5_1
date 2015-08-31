<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Models\MemberTokenModel;
use Log;

class TokenAuth
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
    	if($request->has('token')){
    		$token = $request->input('token');
    		$user = MemberTokenModel::where('token',$token)->whereRaw('last_updated >= DATE_SUB(NOW(),INTERVAL 30 MINUTE)')->orderBy('member_token_id', 'desc')->first();
    		
    		if(!empty($user)){
				
    			$request->merge(array('user_data' => $user));
    			return $next($request);
    		}
    		else{
    			$statusCode = 500;
    			$status = trans("message.rest_status_fail");
    			$response['message'] = trans('message.unauthorized_user') ;
    			return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 500);
    		}
    	}
    	else{
    		$statusCode = 500;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.empty_token') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 500);
    		
    	}
        
    }
}