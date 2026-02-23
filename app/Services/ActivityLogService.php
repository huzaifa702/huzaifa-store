<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(
        string $action,
        string $description = null,
        $user = null,
        $model = null,
        array $oldValues = null,
        array $newValues = null,
    ): ActivityLog {
        $userId = null;
        $userType = null;

        if ($user) {
            $userId = $user->id;
            $userType = get_class($user);
        } elseif (session()->has('admin_id')) {
            $userId = session('admin_id');
            $userType = 'App\\Models\\Admin';
        } elseif (auth()->check()) {
            $userId = auth()->id();
            $userType = 'App\\Models\\User';
        }

        return ActivityLog::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
