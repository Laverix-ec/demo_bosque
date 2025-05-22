<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restriction extends Model
{

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
            ->as('activity')->withPivot('status')->withTimestamps();
    }
}
