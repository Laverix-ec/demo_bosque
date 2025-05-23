<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artifact extends Model
{

    public function accessRequests(): BelongsToMany
    {
        return $this->belongsToMany(AccessRequest::class)
            ->withPivot(['type', 'quantity', 'location', 'description'])->withTimestamps();
    }
}
