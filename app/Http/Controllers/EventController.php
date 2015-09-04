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
	
	public function __construct(AuthRepositoryEloquent $auth){
		$this->auth = $auth;
	
	}
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_event_list($event_id)
    {
    	$event_data = MemberEventModel::where('event_id',$event_id)->first();
    	if(!empty($event_data))
    	{
    		$response['event_details'] = array(
    			'name' => $event_data->name,
    			'date_created' => $event_data->date_created,
    			'number_of_intervals' => $event_data->intervals,
    			'goal_time' => $event_data->goal_time,
    			'is_completed' => $event_data->is_completed,
    			
    		);
    		$laps_data = IntervalRecordModel::where('event_id',$event_id)->orderBy('lap_id','desc')->get();
    		Log::info($laps_data);
    		foreach($laps_data as $lap){
    			
    			$response['laps_details'][$lap->lap_id] = $lap->lap_time;
    		}
    				   			
    		
    		return $this->response_success($response);
    	}
    	else{
    		
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    	}
    	
    }
    
    /**
     * post_add_event
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
    		$valid = ['member_id','name','goal_time','distance','interval'];
    		$response = $this->validation_check($validator,$valid);
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
    		$valid = ['event_id','name','laps'];
    		$response = $this->validation_check($validator,$valid);
    		$errors['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    		 
    	}
    	
    	$laps_array = $request->input('laps');
    	$name = $request->input('name');
    	$event_id = $request->input('event_id');
    	$update_detail = array(
    			'name' => $name,
    			'date_created' => date('Y-m-d H:i:s'),
    		);
    	//Update Event table
    	$event_data = MemberEventModel::where ( 'event_id', $event_id )->update( $update_detail );
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
		/**/
		
    	if($event_data==1){
    		$response['message'] = trans('message.event_updated');
    		return $this->response_success($response);
    	}
    	else{
    		
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    	}
    	
    }
    
    
}
