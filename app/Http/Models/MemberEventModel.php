<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberEventModel extends Model
{
	protected $table = 'event';
	
	protected $primary = ['event_id'];
	
	protected $fillable = ['name', 'member_id', 'goal_time','last_activity'];
	
	public $timestamps = false;
}
