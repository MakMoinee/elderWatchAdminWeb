<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    use HasFactory;

    protected $id = 'deviceID';

    protected $fillable = [
        'deviceID',
        'ip',
        'password',
        'status',
        'userID',
        'username',
    ];
}
