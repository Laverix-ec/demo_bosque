<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{

    public function restrictions(): BelongsToMany
    {
        return $this->belongsToMany(Restriction::class)
            ->as('restriction')->withPivot('status')->withTimestamps();
    }
}
