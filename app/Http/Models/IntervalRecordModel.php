<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class IntervalRecordModel extends Model
{
    protected $table = 'interval_records';
	
	protected $primaryKey = 'interval_records_id';
	
	protected $fillable = ['event_id', 'lap_id', 'lap_time'];
	
	public $timestamps = false;
}
