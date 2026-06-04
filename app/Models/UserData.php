<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'user_data';

    protected $fillable = [
        'user_id', 'name', 'last_name', 'phone_num',
        'phone_code', 'country', 'address', 'city', 'zip',
    ];
}