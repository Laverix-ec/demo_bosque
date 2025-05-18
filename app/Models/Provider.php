<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'commercial_name',
        'ruc',
        'legal_name',
        'contact_name',
        'contact_email'
    ];
}
