<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_num',
        'name',
        'description',
        'company_id',
        'start_date',
        'duration',
        'value',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContractStatus::class,
        ];
    }

    /**
     * Get the company associated with the Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get all of the services for the Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(ServicesContract::class);
    }
}
