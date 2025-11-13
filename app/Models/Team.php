<?php

namespace App\Models;

use App\Models\Retrospective;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function retrospectives(): HasMany
    {
        return $this->hasMany(Retrospective::class);
    }

    public function nextRetroDueBy(): Carbon
    {
        return $this->retrospectives()->orderBy('date', 'desc')->first()->date->addDays($this->retro_cycle_in_days);
    }
}
