<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'genre',
        'publisher',
        'developer',
        'is_multi_player',
        'is_single_player',
        'user_id'
    ];
    protected $casts = [
        'release_date' => 'date',
        'is_multi_player' => 'boolean',
        'is_single_player' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GameReview::class);
    }
}
