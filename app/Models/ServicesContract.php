<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicesContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_id',
        'service_id'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
