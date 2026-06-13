<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class AuditLog
{
    public static function log(string $action, array $details = [])
    {
        $user = auth()->user();

        $entry = [
            'time' => now()->toDateTimeString(),
            'user_id' => $user?->id ?? 'guest',
            'email' => $user?->email ?? 'guest',
            'action' => $action,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'details' => $details,
        ];

        Log::channel('audit')->info(json_encode($entry));
    }
}
