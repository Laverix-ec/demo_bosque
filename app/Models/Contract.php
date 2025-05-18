<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{

    protected $fillable = [
        'contract_number',
        'contract_object',
        'internal_admin_id',
        'department_id',
        'provider_id',
        'product_service',
        'start_date',
        'end_date',
        'status',
        'addenda',
        'auto_renewal',
        'observation',
        'account_code',
        'payment_terms',
        'contract_cost',
        'approved_additional_costs',
        'approved_budget',
        'total_cost',
        'cost_vs_budget'
    ];

    protected $casts = [
        'contract_cost' => MoneyCast::class,
        'approved_additional_costs' => MoneyCast::class,
        'approved_budget' => MoneyCast::class,
        'total_cost' => MoneyCast::class,
        'cost_vs_budget' => MoneyCast::class,
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'internal_admin_id');
    }

    public function deliverables(): HasMany
    {
        return $this->hasMany(Deliverable::class);
    }
}
