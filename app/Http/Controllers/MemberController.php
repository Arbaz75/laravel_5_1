<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\UserModel;
use App\Http\Models\MemberEventModel;
use Log;

class MemberController extends Controller
{
	
	public function __construct()
	{
		
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_user_detail(Request $request,$member_id)
    {
    	$user = UserModel::where('member_id',$member_id)->first();
    	
    	if(!empty($user)){
    		
    		$response = array(
    				'username' => $user->name,
    				'email'    => $user->email_id,
    				'date_created' => $user->date_created,
    				'city'	   => $user->city,
    		);
    		
    		return $this->response_success($response);
    		
    	}
    	else{
    		
    		$response['message'] = trans('message.not_exist') ;
    		return $this->response_fail($response);
    	}
    	
        
    }
    
    /**
     * post_update
     * 
     * Update the user data
     * 
     * @param Request $request
     * @param unknown $member_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_profile_update(Request $request,$member_id)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id' => 'required|exists:members|digits',
    			'first_name'=> 'required',
    			'last_name'=> 'required',
    			]);
    	 
    	if ($validator->fails()) {
    		$valid = ['member_id','first_name','last_name'];
    		$response = $this->validation_check($validator,$valid);
    		$response['message'] = trans('message.invalid_request') ;
    		return $this->response_fail($response);
    	}
    	
    	$first_name = $request->input('first_name');
    	$last_name = $request->input('last_name');
    	
        $update_details = array(
        	first_name => $first_name,
        	last_name => $last_name,
        );
    	$user = UserModel::where('member_id',$member_id)->update($update_details);
    	
    	if(!empty($user)){
    		$response['message'] = trans("message.update_data_success" );
    		return $this->response_success($response);
    	}
    	else{
    		$response['message'] = trans('message.invalid_request') ;
    		return $this->response_fail($response);
    		
    	}
    	
    	
    }
    
    /**
     * Fetch User Event List
     * 
     * @param  $member_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_events($member_id, $start=0, $length=10)
    {	Log::info($start." ".$length);
    	$event_count = MemberEventModel::where('member_id',$member_id)->count();
    	$event_data = MemberEventModel::where('member_id',$member_id)->skip($start)->take($length)->get();
    	if(!empty($event_data)){
    			
    		$response['event_details'] = $event_data;
    		$response['total_events'] = $event_count;
    		return $this->response_success($response);
    	}
    	else{
    		$response['message'] = trans('message.not_exist');
    		return $this->response_fail($response);
    	}
    	
    }

}