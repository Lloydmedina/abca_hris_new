<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $guarded  = [];//FILLABLE SA IMONG GANAHAN LANG
    //protected $fillable = ['username', 'first_name', 'last_name', 'email', 'web_password', 'salt'];
}
