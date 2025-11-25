<?php

namespace App\Helpers;

use App\Models\PusherSetting;

class PusherConfig
{
    public static function apply()
    {
        $s = PusherSetting::first();

        config([
            'broadcasting.connections.pusher.key' => $s ? $s->key : env('PUSHER_APP_KEY'),
            'broadcasting.connections.pusher.secret' => $s ? $s->secret : env('PUSHER_APP_SECRET'),
            'broadcasting.connections.pusher.app_id' => $s ? $s->app_id : env('PUSHER_APP_ID'),
            'broadcasting.connections.pusher.options.cluster' => $s ? $s->cluster : env('PUSHER_APP_CLUSTER'),
            'broadcasting.connections.pusher.options.port' => $s ? $s->port : env('PUSHER_PORT', 443),
            'broadcasting.connections.pusher.options.scheme' => $s ? $s->scheme : env('PUSHER_SCHEME', 'https'),
        ]);
    }
}
