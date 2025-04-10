<?php

namespace App\Traits;

use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsUserActivity
{
    public function logActivity($actionType, $description, $details = [])
    {
        return UserActivityLog::create([
            'user_id' => auth()->id(),
            'action_type' => $actionType,
            'description' => $description,
            'details' => $details,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
} 