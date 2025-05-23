<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessRequest extends Model
{

    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function commercialUnit(): BelongsTo
    {
        return $this->belongsTo(CommercialUnit::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function artifacts(): BelongsToMany
    {
        return $this->belongsToMany(Artifact::class)
            ->withPivot(['type', 'quantity', 'location', 'description'])->withTimestamps();
    }

    public function accessRequestSchedules(): HasMany
    {
        return $this->hasMany(AccessRequestSchedule::class);
    }
}
