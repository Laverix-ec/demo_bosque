<?php

namespace App\Concerns;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContacts
{

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

}
