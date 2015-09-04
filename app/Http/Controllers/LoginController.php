<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\AuthRepositoryEloquent;
use Validator;
use Hash;
use Log;
use App\Http\Models\UserModel;
use App\Http\Models\MemberTokenModel;

class LoginController extends Controller
{
	
	/**
	 * Contructor Method
	 * 
	 * @param AuthRepositoryEloquent $auth
	 */
    public function __construct(AuthRepositoryEloquent $auth){
        $this->auth = $auth;
        
    }
    
    /**
     * post_login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'email_id' => 'required|exists:members|email',
    			'password' => 'required|min:5',
    			]);
    	
    	if ($validator->fails()) {
    		Log::info("post_login:Validation Fail");
    		$response['message'] = trans('message.validation_error') ;
    		return $this->response_fail($response);
    	}
    	
        $email = $request->input('email_id');
        $password =$request->input('password');
		//*** Fetch User Details ***//
        $user = UserModel::where('email_id', $email)->first();
        if(!empty($user)){

            if(Hash::check($password, $user->password) && $user->is_active == 1){
				$user->token = $this->auth->generate_token_for_user($user->member_id);
				$response = $user;
                $response['message'] = trans("message.login_success" );
                return $this->response_success($response);
                
            }
            else{
            	Log::info("post_login:Invalid Username or Password");
            	$response['message'] = trans('message.invalid_credentials');
            	return $this->response_fail($response);
            }
       
        }
    }
    
  
    /**
     * get_logout
     * Function to logout user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_logout(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'member_id' => 'required|exists:member_token',
    			'token' => 'required|min:50',
    			]);
    	if ($validator->fails()) {
    		$response['message'] = trans('message.invalid_request') ;
    		return $this->response_fail($response);
    	}
    	
    	$member_id = $request->input("member_id");
    	$token = $request->input("token");
    	//*** check for authorize user ***//
    	$check_id = MemberTokenModel::where('member_id',$member_id)->where('token',$token)->value("token");
    	
    	if(empty($check_id)){
    		$response['message'] = trans('message.invalid_token') ;
    		return $this->response_fail($response);
    	}
    	else{
    		//***Remove User Token ***//
    		$update_data['token'] = '';
    		$update = MemberTokenModel::where("member_id",$member_id)->where("token",$token)->update($update_data);
    		Log::info("get_logout:User Token Removed Successfully");
    		$response['message']= trans("message.logout_success");
    		return $this->response_success($response);
    
    	}
    }
	
    
    /**
     * post_forget_password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email_id' => 'required|exists:members|email',
                ]);

        if ($validator->fails()) {
        	$valid = ['email_id'];
        	$response = $this->validation_error_mapper($validator,$valid);
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
        	$apiStatusCode = 200;
        	$response['message'] = trans("message.reset_password_success" );
        	return $this->response_success($response);
        	
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
    			'first_name' => 'required|alpha',
    			'last_name' => 'required|alpha',
    			'password' => 'required|min:5',
    			'city' => 'required|alpha',
    			]);
    	if ($validator->fails()) {
    		Log::info("post_register :Validation Fails");
    		$valid = ['email_id','first_name','last_name','password','city'];
    		$response = $this->validation_error_mapper($validator,$valid);
    		$response['message'] = trans('message.invalid_request') ;
        	return $this->response_fail($response);
        	
    	}
    	
    	$first_name = $request->input('firt_name');
    	$last_name = $request->input('last_name');
    	$email_id = $request->input('email_id');
    	$password = Hash::make($request->input('password'));
    	$city = $request->input('city');
    	
    	$user_data = array(
    		'first_name' => $first_name,
    		'last_name' => $last_name,
    		'email_id' => $email_id,
    		'password' => $password,
    		'city' => $city, 
    	);
    	$user_data['date_created'] = date('Y-m-d H:i:s'); 
    	$user_data['last_activity'] = date('Y-m-d H:i:s');
    	/*Insert User Data Into DataBase */
    	
    	$user = UserModel::create($user_data);
    	Log::info("post_register :Data Inserted successfully");
    	if(!empty($user))
    	{
	    	$response['user_detail'] = $user;
	    	$response['message'] = trans("message.register_success" );
	    	return $this->response_success($response);
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
    		Log::info("post_change_password :Validation Fails");
    		$valid = ['email_id','password','new_password'];
    		$response = $this->validation_error_mapper($validator,$valid);
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
    		Log::info("post_change_password :Password change successfully. Row affected: ".$row_affect);
    		$response['message'] = trans("message.change_password_success" );
    		return $this->response_success($response);
    	}
    	else{
    		$response['message'] = trans('message.invalid_credentials');
    		return $this->response_fail($response);
    	}
    	
    }
    
 } 
  