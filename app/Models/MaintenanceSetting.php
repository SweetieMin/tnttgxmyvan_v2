<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSetting extends Model
{
    protected $fillable = [
        'is_maintenance',
        'secret_key',
        'message',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_maintenance' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    
}
