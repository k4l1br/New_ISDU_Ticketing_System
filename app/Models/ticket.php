<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{

    protected $fillable = [
        'full_name',
        'position',
        'designation',
        'contact_number',
        'email_address',
        'req_office',
        'reference',
        'authority',
        'status',
        'unit_responsible',
        'description',
    ];
}
