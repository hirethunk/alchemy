<?php

namespace App\Models;

use App\Models\Entry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retrospective extends Model
{
    /** @use HasFactory<\Database\Factories\RetrospectiveFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    public static function fromTemplate(Team $team)
    {
        return self::create([
            'team_id' => $team->id,
            'date' => now(),
        ]);
    }
}
