<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reqOffice extends Model
{
    protected $fillable = [
        'req_office',
    ];

    /**
     * Accessor for camelCase compatibility
     * This allows both $office->reqOffice and $office->req_office to work
     */
    public function getReqOfficeAttribute()
    {
        return $this->attributes['req_office'];
    }

    /**
     * Table name (Laravel will pluralize the model name by default)
     */
    protected $table = 'req_offices';
}