<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\UserModel;
use Log;

class MemberController extends Controller
{
	
	public function __construct()
	{
		Log::info("loggged");
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_user_detail(Request $request,$member_id)
    {
    	
    	/*$validator = Validator::make($request->all(), [
    			'member_id' => 'required|exists:members|digits',
    			]);
    	
    	if ($validator->fails()) {
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$errors['message'] = trans('message.invalid_request') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $errors ), 203);
    		 
    	}*/
    	//$member_id  = $request->input('member_id');
    	$user = UserModel::where('member_id',$member_id)->first();
    	
    	if(!empty($user)){
    		
    		$response = array(
    				'username' => $user->name,
    				'email'    => $user->email_id,
    				'date_created' => $user->date_created,
    				'city'	   => $user->city,
    		);
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    		
    	}
    	else{
    		$status = trans("message.rest_status_fail" );
    		$statusCode = 203;
    		$response['message'] = trans('message.not_exist') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response), 203);
    	}
    	
        
    }
    
    /**
     * post_update
     * 
     * Update the user data
     * @param Request $request
     * @param unknown $member_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_update(Request $request,$member_id)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id' => 'required|exists:members|digits',
    			//Data Need to be updated will be ADDED here
    			]);
    	 
    	if ($validator->fails()) {
    		$valid = ['member_id'];
    		$msgs = $validator->errors();
    		for($i=0; $i< count($valid);$i++){
    			if($msgs->has($valid[$i])){
    				$response['validation_error'][$valid[$i]] = $msgs->first($valid[$i]);
    			}
    		}
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    		 
    	}
        
    	$user = UserModel::where('member_id',$member_id)->update([]);
    	
    	if(!empty($user)){
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		$response['message'] = trans("message.update_data_success" );
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    	}
    	else{
    		$statusCode = 203;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    		 
    		
    	}
    	
    	
    }
    
    public function get_event($member_id)
    {
    	$event_data = MemberEventModel::where('member_id',$member_id)->get();
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
    		$response['message'] = trans('message.not_exist');
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 203);
    	}
    	
    }

}