<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ordering',
    ];

    protected $casts = [
        'ordering' => 'integer',
    ];
}
