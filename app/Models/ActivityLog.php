<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_type', 'user_id', 'action', 'description',
        'model_type', 'model_id', 'ip_address', 'user_agent',
        'old_values', 'new_values',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user()
    {
        return $this->morphTo();
    }
}
