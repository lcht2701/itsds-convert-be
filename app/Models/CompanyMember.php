<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'company_id',
        'company_address_id'
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function companyAddress()
    {
        return $this->belongsTo(CompanyAddress::class, 'company_addres_id');
    }
}
