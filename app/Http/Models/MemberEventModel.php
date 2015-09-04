<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberEventModel extends Model
{
	protected $table = 'event';
	
	protected $primaryKey  = 'event_id';
	
	protected $hidden = ['last_updated','date_created','event_id'];
	
	protected $fillable = ['name', 'member_id', 'goal_time','last_activity','date_created'];
	
	public $timestamps = false;
}
