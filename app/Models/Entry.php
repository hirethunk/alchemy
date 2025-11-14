<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use App\Models\Retrospective;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
    protected $guarded = [];

    public function retrospective(): BelongsTo
    {
        return $this->belongsTo(Retrospective::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
