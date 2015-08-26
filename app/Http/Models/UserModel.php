<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'members';
    
    protected $primary = ['member_id'];

    protected $fillable = ['name', 'email_id', 'password','city','date_created','last_activity'];

    public $timestamps = false;
}
