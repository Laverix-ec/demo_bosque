<?php

namespace App\Models;

use App\Concerns\HasContacts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CommercialUnit extends Model implements HasMedia
{
    use InteractsWithMedia, HasContacts;

    protected $fillable = [
        'zone',
        'local_code',
        'name',
        'category_id',
        'ruc',
        'property_code',
        'location',
        'tenant_id',
        'co_tenant_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CommercialCategory::class, 'category_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function coTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'co_tenant_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(UnitScore::class);
    }
}
