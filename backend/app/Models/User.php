<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'profile_image',
        'full_name',
        'age',
        'street',
        'neighborhood',
        'state',
        'biography',
    ];
}
