<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_event_list($event_id)
    {
    	$event_data = MemberEventModel::where('event_id',$event_id)->get();
    	if(!empty($event_data))
    	{
    		$response = array(
    			'name' => $event_data->name,
    			'date_generated' => $event_data->date_generated,
    			
    		);
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    	}
    	else{
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    	}
    	
    }
    
    /**
     * post_event
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function post_event(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id'=> 'required',
    			'name' => 'required|min:5',
    			]);
    	
    	if ($validator->fails()) {
    		$valid = ['member_id','name'];
    		$msgs = $validator->errors();
    		for($i=0; $i< count($valid);$i++){
    			if($msgs->has($valid[$i])){
    				$response['validation_error'][$valid[$i]] = $msgs->first($valid[$i]);
    			}
    		}
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$errors['message'] = trans('message.invalid_request');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $errors ), 203);
    		 
    	}
    	
    	//Parameter Need to be Added
    	/*$name = $request->input('');
    	$member_id = $request->input('');
    	$time = $request->input('');
    	$interval = $request->input('');
    	$value = $request->input('');*/
    	
    	$event_detail = array(
    			'name' => $name,
    			'date_generated' => date('Y-m-d H:i:s'),
    			
    		);
    	$event_data = MemberEventModel::create([$event_detail]);
    	if(!empty($event_data)){
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		$response['event_detail'] = $event_data;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    	}
    	else{
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    	}
    	
    }
    
    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_event_update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'event_id'=> 'required|exists:event',
    			'name' => 'required|min:5',
    			]);
    	
    	if ($validator->fails()) {
    		$valid = ['event_id','name'];
    		$msgs = $validator->errors();
    		for($i=0; $i< count($valid);$i++){
    			if($msgs->has($valid[$i])){
    				$response['validation_error'][$valid[$i]] = $msgs->first($valid[$i]);
    			}
    		}
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$errors['message'] = trans('message.invalid_request');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $errors ), 203);
    		 
    	}
    	
    	//Parameter Need to be Added
    	/*$name = $request->input('');
    	$member_id = $request->input('');
    	$time = $request->input('');
    	$interval = $request->input('');
    	$value = $request->input('');*/
    	
    	$update_detail = array(
    			'name' => $name,
    			'date_generated' => date('Y-m-d H:i:s'),
    			
    		);
    	
    	$event_data = MemberEventModel::where('event_id', $event_id)->update([$event_detail]);
    	if(!empty($event_data)){
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		$response['event_detail'] = $event_data;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    	}
    	else{
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    	}
    	
    }
}
