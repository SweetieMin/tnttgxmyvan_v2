<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;

class ResponseToastHelper
{
    public static function successRedirect(
        string $route,
        string $message,
        array $replace = [],
        array $params = [] 
    ) {
        $replace['time'] = $replace['time'] ?? now()->format('d/m/Y H:i:s');
    
        return Redirect::route($route, $params) 
            ->with([
                'success' => __($message, $replace),
                'description' => 'Lúc: ' . $replace['time'],
            ]);
    }
    

    public static function errorRedirect(string $route, string $message, array $replace = [], array $params = [] )
    {
        return Redirect::route($route, $params) 
            ->with([
                'error' => __($message, $replace),
                'description' => now()->format('d/m/Y H:i:s'),
            ]);
    }
}
