<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;

class ResponseToastHelper
{
    public static function successRedirect(string $route, string $message, array $replace = [])
    {
        $replace['time'] = $replace['time'] ?? now()->format('d/m/Y H:i:s');

        return Redirect::route($route)
            ->with([
                'success' => __($message, $replace),
                'description' => $replace['time'],
            ]);
    }

    public static function errorRedirect(string $route, string $message, array $replace = [])
    {
        return Redirect::route($route)
            ->with([
                'error' => __($message, $replace),
                'description' => now()->format('d/m/Y H:i:s'),
            ]);
    }
}
