<?php

namespace App\Http\Models;

use App\Http\Models\MemberTokenModel;
use App\Http\Models\UserModel;
use DB;

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
        MemberTokenModel::updateOrCreate(array('member_id' => $userId),array(
            'token'=> $token,
            'member_id' => $userId
         ));
        return $token;
    }
    
    public function update_user_verification_token($member_id)
    {
    	$PasswordVerificationToken = str_random(40);
    	UserModel::updateOrCreate(array('member_id' => $member_id),array(
    	'token'=> $PasswordVerificationToken,
    	'member_id' => $userId)
    	);
    	return $PasswordVerificationToken;
    	
    }
    
    public function send_password_email($user_detail,$token)
    {
    	//Send email to user with Token
    }
}