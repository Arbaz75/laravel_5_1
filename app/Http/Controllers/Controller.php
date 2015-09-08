<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {
	use DispatchesJobs, ValidatesRequests;
	
	/**
	 * validation_error_mapper
	 * 
	 * @param unknown $validator
	 * @param unknown $valid
	 * @return unknown
	 */
	public function validation_error_mapper($validator, $valid) {
		$msgs = $validator->errors ();
		$array_count = count ( $valid );
		for($i = 0; $i < $array_count; $i ++) {
			if ($msgs->has ( $valid [$i] )) {
				$response ['validation_error'] [$valid [$i]] = $msgs->first ( $valid [$i] );
			}
		}
		return $response;
	}
	
	/**
	 * response_fail
	 *
	 * @param
	 *        	$response
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function response_fail($response) {
		$status = trans ( "message.rest_status_fail" );
		$apiStatusCode = 203;
		return response ()->json ( array (
				"status" => $status,
				"status_code" => $apiStatusCode,
				"response" => $response 
		), 203 );
	}
	
	/**
	 * response_success
	 *
	 * @param
	 *        	$response
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function response_success($response) {
		$status = trans ( "message.rest_status_success" );
		$apiStatusCode = 200;
		return response ()->json ( array (
				"status" => $status,
				"status_code" => $apiStatusCode,
				"response" => $response 
		), 200 );
	}

	/**
	 * exception_handler
	 *
	 * @param $response
	 * @param int $response_code
	 * @param int $status_code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function exception_handler($response, $response_code = 500, $status_code = 500){
		Log::info("Exception handler returning response");
		$status = trans('message.rest_status_fail');
		return response()->json(array("status" => $status, "status_code" => $status_code) + $response, $response_code);
	}
}


