<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model {
    protected $fillable = ['post_id', 'user_id', 'body', 'is_approved'];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}