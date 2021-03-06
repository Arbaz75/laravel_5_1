<?php

namespace App\Http\Models;

use App\Http\Models\MemberTokenModel;
use App\Http\Models\UserModel;
use DB;
use Mail;

class AuthRepositoryEloquent
{
    
    //*** Generating New token for the user ***//
    public function generate_token_for_user($userId)
    {
        if(empty($userId)){
            return false;
        }
        $token = str_random(50);
        $token_data = DB::table('member_token')->where("member_id",$userId)->value('member_id');
        
        //*** Update User Token ***//
        MemberTokenModel::create(array(
            'token'=> $token,
            'member_id' => $userId,
            
         ));
        return $token;
    }
    
    public function update_user_verification_token($member_id)
    {
    	$PasswordVerificationToken = str_random(30);
    	MemberTokenModel::updateOrCreate(array('member_id' => $member_id),array(
    	'token'=> $PasswordVerificationToken,
    	'member_id' => $member_id)
    	);
    	return $PasswordVerificationToken;
    	
    }
    
    public function send_password_email($user_detail,$token)
    {
    	Mail::send('emails.password', [$user_detail,$token], function($message,$user_detail){
		$message->from('admin@admin.com', 'Admin');
		$message->to($user_detail->email_id);
		Log::info('send_password_email:Mail sent Successfully');
		
		});
    }

    /**
     * time_validate
     * Validates HH:mm format
     *
     * @param $time
     * @return int
     */
    public function time_validate($time)
    {
    	return preg_match("/^(2[0-3]|[01][0-9]):([0-5][0-9])$/", $time);
    }
    
}