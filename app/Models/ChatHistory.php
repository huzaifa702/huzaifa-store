<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'sender',
        'type',
        'products_data',
        'image_path',
    ];

    protected $casts = [
        'products_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
