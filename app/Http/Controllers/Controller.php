<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {
	use DispatchesJobs, ValidatesRequests;
	
	/**
	 * validation_check
	 * 
	 * @param unknown $validator
	 * @param unknown $valid
	 * @return unknown
	 */
	public static function validation_check($validator, $valid) {
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
		$statusCode = 203;
		return response ()->json ( array (
				"status" => $status,
				"status_code" => $statusCode,
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
		$statusCode = 200;
		return response ()->json ( array (
				"status" => $status,
				"status_code" => $statusCode,
				"response" => $response 
		), 200 );
	}
}


