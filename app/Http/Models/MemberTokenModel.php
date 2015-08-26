<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTokenModel extends Model
{
   	protected $table = 'member_token';

   	protected $primaryKey = 'member_token_id';

   	protected $fillable = ['token','member_id'];
   	
   	protected $guarded = ['member_token_id'];
   	
   	public $timestamps = false;

}
