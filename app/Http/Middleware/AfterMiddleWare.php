<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class AfterMiddleWare
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
        $response = $next($request);
        $execution = round((microtime(true)-LARAVEL_START)*1000,4);
        if($response->headers->get('content-type') == 'application/json'){
        	$response_content = $response->getContent();
        	$response_content  = json_decode($response_content);
        	$response_content->api_execution_time = $execution;
        	$response_content = json_encode($response_content);
        	$response->setContent($response_content);
          }	
       return $response;
        
    }
    
    /**
     * perform action after HTTP response has already been sent to browser.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    public function terminate($request, $response) {
    	
    	if(defined('LARAVEL_START')){
    		Log::info($request->getMethod() . "[" . $request->path() . "] Execution time : ".round((microtime(true)-LARAVEL_START)*1000,4)." ms");
    	}
    }
}
