<?php

namespace App\Models;

use App\Concerns\HasContacts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasContacts;

    public function residentialSector(): BelongsTo
    {
        return $this->belongsTo(ResidentialSector::class, 'residential_sector_id');
    }
}
