<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = ['user_id', 'email', 'type', 'subject', 'status', 'resend_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a marketing email was already sent to this email in the last 24 hours.
     */
    public static function canSendMarketing(string $email): bool
    {
        return !static::where('email', $email)
            ->where('type', 'marketing')
            ->where('status', 'sent')
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();
    }
}
