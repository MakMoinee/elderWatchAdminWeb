<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $id = 'docID';

    protected $fillable = [
        'userID',
        'email',
        'firstName',
        'middleName',
        'lastName',
        'address',
        'password',
        'phoneNumber',
        'registeredDate',
        'userType',
    ];
}
