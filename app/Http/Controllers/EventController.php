<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\MemberEventModel;
use App\Http\Models\IntervalRecordModel;
use App\Http\Models\AuthRepositoryEloquent;
use Validator;
use Log;

class EventController extends Controller
{
	
	/**
	 * Constructor Method
	 * 
	 * @param AuthRepositoryEloquent $auth
	 */
	public function __construct(AuthRepositoryEloquent $auth){
		$this->auth = $auth;
		Log::info("EventController initialized");
	}
   	
	/**
	 * get_event
	 * 
	 * @param unknown $event_id
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function get_event($event_id)
    {
    	$event_data = MemberEventModel::where('event_id',$event_id)->first();
    	if(!empty($event_data))
    	{
			$event_data->laps = IntervalRecordModel::where('event_id',$event_id)->orderBy('lap_id','desc')->get();
			$response['event_details'] = $event_data;
    		return $this->response_success($response);
    	}
    	else{
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    	}
    }
    
    /**
     * post_add_event
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function post_add_event(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id'=> 'required|numeric',
    			'name' => 'required|min:5',
    			'goal_time' => 'required|date_format:"H:i"',
    			'distance' => 'required|numeric',
    			'interval' => 'required|numeric',
    			
    			]);
    	
    	if ($validator->fails()) {
    		Log::info('post_add_event:Validation Fails');
    		$valid = ['member_id','name','goal_time','distance','interval'];
    		$response = $this->validation_error_mapper($validator,$valid);
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    		 
    	}
    	
    	$name = $request->input('name');
    	$member_id = $request->input('member_id');
    	$goal_time = $request->input('goal_time');
    	$interval = $request->input('interval');
    	$distance = $request->input('distance');
    	
    	$event_detail = array(
    			'member_id' => $member_id,
    			'name' => $name,
    			'goal_time' => $goal_time,
    			'distance' => $distance,
    			'interval' => $interval,
    			'date_created' => date('Y-m-d H:i:s'),
    			
    		);
    	$event_data = MemberEventModel::create($event_detail);
    	Log::info('post_add_event:Data Inserted successfully');
    	if(!empty($event_data)){
    		$response['event_detail'] = $event_data;
    		$response['message'] = trans('message.data_insert_success');;
    		return $this->response_success($response);
    	}
    	else{
    		
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
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
    			'laps' => 'array',
    			]);
    	
    	if ($validator->fails()) {
    		Log::info('post_event_update:Validation Fails');
    		$valid = ['event_id','name','laps'];
    		$response = $this->validation_error_mapper($validator,$valid);
    		$errors['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    	}
    	$laps_array = $request->input('laps');
    	$name = $request->input('name');
    	$event_id = $request->input('event_id');
    	
    	/*Update Event table*/
   		$event_data = MemberEventModel::find($event_id);
    	$event_data->name  = $name;
    	$event_data->date_created  = date('Y-m-d H:i:s');
    	$event_data->save();
    	
    	$laps_count = count($laps_array)+1;
		//*Update Interval Records*//
		foreach($laps_array as  $lap){
				$valid_time = $this->auth->time_validate($lap['lap_time']);
				if($valid_time == 1){
					IntervalRecordModel::updateOrCreate(array('event_id' => $event_id, 'lap_id' => $lap['lap_id']), [
						'lap_id' => $lap['lap_id'],
						'lap_time' => $lap['lap_time'],
					]);
				}
				else{
					$response['message'] = trans('message.invalide_time');
					return $this->response_fail($response);
				}
		}
		if(!empty($event_data)){
			$response['event_detail'] = $event_data;
    		$response['message'] = trans('message.event_updated');
    		return $this->response_success($response);
    	}
    	else{
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    	}
    	
    }
    
}
