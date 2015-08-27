<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {	
    	
    	$status = trans('message.rest_status_fail');
    	$exception_code = $e->getCode();
    	$developerMessage['exception_type'] = get_class($e);
    	$developerMessage['error_info'] = $e->getFile().",". $e->getLine();
    	$developerMessage['error_code'] =  $exception_code;
    	return response()->json(array("status" => $status, "status_code" => 503, "developer_message" => $developerMessage ),500);
    	
        
    	#return parent::render($request, $e);
    }
}
