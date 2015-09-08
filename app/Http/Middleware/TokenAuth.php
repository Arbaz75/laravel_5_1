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
    			MemberTokenModel::where('token',$token)->update(['requests'=> $user->requests + 1]);
    			return $next($request);
    		}
    		else{
				return app('\App\Http\Controllers\LoginController')->exception_handler(array("response" => array( 'message' => trans('message.unauthorized_user')) ));
    		}
    	}
    	else{
			return app('\App\Http\Controllers\LoginController')->exception_handler(array("response" => array( 'message' => trans('message.empty_token')) ));
    	}
        
    }
}