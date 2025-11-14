<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PusherSetting extends Model
{
    protected $fillable = [
        'app_id',
        'key',
        'secret',
        'cluster',
        'port',
        'scheme',
    ];
}
