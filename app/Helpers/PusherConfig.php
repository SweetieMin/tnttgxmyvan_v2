<?php

use App\Models\PusherSetting;

class PusherConfig
{
    public static function apply()
    {
        $s = PusherSetting::first();
        if (!$s) return;

        config([
            'broadcasting.connections.pusher.key' => $s->key,
            'broadcasting.connections.pusher.secret' => $s->secret,
            'broadcasting.connections.pusher.app_id' => $s->app_id,
            'broadcasting.connections.pusher.options.cluster' => $s->cluster,
            'broadcasting.connections.pusher.options.port' => $s->port,
            'broadcasting.connections.pusher.options.scheme' => $s->scheme,
        ]);
    }
}
