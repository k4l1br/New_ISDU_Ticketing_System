<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{

    protected $fillable = [
        'fullName',
        'position',
        'designation',
        'contactNumber',
        'emailAddress',
        'reqOffice',
        'reference',
        'authority',
        'status',
        'unitResponsible',
    ];
}
