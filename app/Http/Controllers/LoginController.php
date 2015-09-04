<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\AuthRepositoryEloquent;
use Validator;
use DB;
use Hash;
use Log;
use App\Http\Models\UserModel;

class LoginController extends Controller
{
    public function __construct(AuthRepositoryEloquent $auth){
        $this->auth = $auth;
        
    }
    
    public function post_login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'email_id' => 'required|exists:members|email',
    			'password' => 'required|min:5',
    			]);
    	
    	if ($validator->fails()) {
    		$status = trans('message.rest_status_fail') ;
    		$statusCode = 203;
    		$response['message'] = trans('message.validation_error') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response), 203);
    		
    	}
    	
        $email = $request->input('email_id');
        $password =$request->input('password');
		//*** Fetch User Details ***//
        $user = UserModel::where('email_id', $email)->first();
        if($user != Null){

            if(Hash::check($password, $user->password) && $user->is_active == 1){
				$user->token = $this->auth->generate_token_for_user($user->member_id);
				$response = $user;
                $status = trans("message.rest_status_success" );
                $statusCode = 200;
                $message = trans("message.login_success" );
                return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response ), 200);
                
            }
            else{
            	$response['message'] = trans('message.invalid_credentials');
            	return $this->response_fail($response);
            }
       
        }
    }
    
    //*** Function to logout user ***//
    public function get_logout(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id' => 'required|exists:member_token',
    			'token' => 'required|min:50',
    			]);
    	if ($validator->fails()) {
    		$statusCode = 400;
    		$status = trans("message.rest_status_fail");
    		$response['message'] = trans('message.invalid_request') ;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 203);
    	}
    	
    	$member_id = $request->input("member_id");
    	$token = $request->input("token");
    	//*** check for authorize user ***//
    	$check_id = DB::table('member_token')->where('member_id',$member_id)->where('token',$token)->value("token");
    	
    	if($check_id == Null){
    		$response['message'] = trans('message.invalid_token') ;
    		return $this->response_fail($response);
    	}
    	else{
    		//***Remove User Token ***//
    		$update_data['token'] = '';
    		$update = DB::table("member_token")->where("member_id",$member_id)->where("token",$token)->update($update_data);
    		$status = trans("message.rest_status_success" );
    		$response['message']= trans("message.login_success");
    		$statusCode = 200;
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
    
    	}
    }
	
    
    /**
     * post_forget_password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email_id' => 'required|exists:members|email',
                ]);

        if ($validator->fails()) {
        	$valid = ['email_id'];
        	$response = $this->validation_check($validator,$valid);
            $response['message'] = trans('message.invalid_request') ;
            return $this->response_fail($response);
        }
        $email = $request->input('email_id');
        $user_data = UserModel::where('email_id', $email)->first();

        $user_detail['name'] = $user_data->name;
        $user_detail['email'] = $email;
        $user_detail['member_id'] = $user_data->member_id;
        $member_id = $user_data->member_id;
        
        $PasswordVerificationToken= $this->auth->update_user_verification_token($member_id);
        $mail = $this->auth->send_password_email($user_detail,$PasswordVerificationToken);
        if(!empty($mail)){
        	
        	$status = trans("message.rest_status_success" );
        	$statusCode = 200;
        	$response['message'] = trans("message.reset_password_success" );
        	return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response, ), 200);
        	
        }
        else{
        	$response['message'] = trans('message.invalid_request') ;
        	return $this->response_fail($response);
        	
        }
        
    }
    
    /**
     * post_register
     * 
     * @param Request $request
     */
    public function post_register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'email_id' => 'required|unique:members|email',
    			'name' => 'required|alpha',
    			'password' => 'required|min:5',
    			'city' => 'required|alpha',
    			]);
    	if ($validator->fails()) {
    		$valid = ['email_id','name','password','city'];
    		$response = $this->validation_check($validator,$valid);
    		$response['message'] = trans('message.invalid_request') ;
        	return $this->response_fail($response);
        	
    	}
    	
    	$name = $request->input('name');
    	$email_id = $request->input('email_id');
    	$password = Hash::make($request->input('password'));
    	$city = $request->input('city');
    	
    	$user_data = array(
    		'name' => $name,
    		'email_id' => $email_id,
    		'password' => $password,
    		'city' => $city, 
    	);
    	$user_data['date_created'] = date('Y-m-d H:i:s'); 
    	$user_data['last_activity'] = date('Y-m-d H:i:s');
    	/*Insert User Data Into DataBase */
    	$user = UserModel::create($user_data);
    	if(!empty($user))
    	{
	    	$response = $user;
	    	$status = trans("message.rest_status_success" );
	    	$statusCode = 200;
	    	$response['message'] = trans("message.register_success" );
	    	return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response), 200);
    	}
    	else{
    		$response['message'] = trans('message.invalid_request') ;
    		return $this->response_fail($response);
    	}
    	
    }
    
    /**
     * post_change_password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_change_password(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'email_id' => 'required|exists:members|email',
    			'password' => 'required|min:5',
    			'new_password' => 'required|min:5|different:password',
    			]);
    	if ($validator->fails()) {
    		$valid = ['email_id','password','new_password'];
    		$response = $this->validation_check($validator,$valid);
    		$response['message'] = trans('message.invalid_request');
    		return $this->response_fail($response);
    		 
    	}
    	
    	$email_id = $request->input('email_id');
    	$password = $request->input('password');
    	$newpassword = Hash::make($request->input('new_password'));
    	
    	$valid_user = UserModel::where('email_id',$email_id)->first();
    	
    	if(Hash::check($password, $valid_user->password) && $valid_user->is_active == 1){
    		//* Update User Password *// 
    		$row_affect = UserModel::where('email_id',$email_id)->update(['password' =>$newpassword]);
    		Log::info($row_affect);
    		$status = trans("message.rest_status_success" );
    		$statusCode = 200;
    		$response['message'] = trans("message.change_password_success" );
    		return response()->json(array("status" => $status, "status_code" => $statusCode, "response" => $response), 200);
    	}
    	else{
    		
    		$response['message'] = trans('message.invalid_credentials');
    		return $this->response_fail($response);
    	}
    	
    }
    
 } 
  