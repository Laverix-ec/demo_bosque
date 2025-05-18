<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitScore extends Model
{

    protected $fillable = [
        'commercial_unit_id',
        'evaluation_criteria_id',
        'score',
        'comment',
        'evaluation_date'
    ];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }
}
