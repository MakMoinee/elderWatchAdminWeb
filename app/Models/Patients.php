<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;

    protected $id = 'patientID';

    protected $fillable = [
        'patientID',
        'birthDate',
        'firstName',
        'fullName',
        'middleName',
        'lastName',
        'address',
        'deviceID',
    ];
}
