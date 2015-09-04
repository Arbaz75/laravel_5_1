<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{	
	protected $table = 'members';
    
    protected $primaryKey = 'member_id';

    protected $hidden = ['password','last_activity','last_updated'];

    protected $fillable = ['name', 'email_id', 'password','city','date_created','last_activity'];

    public $timestamps = false;
}
