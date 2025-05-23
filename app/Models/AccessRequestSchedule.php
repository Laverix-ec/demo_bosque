<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessRequestSchedule extends Model
{

    public function accessRequest(): BelongsTo
    {
        return $this->belongsTo(AccessRequest::class);
    }
}
