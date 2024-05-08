<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'tax_code',
        'company_website',
        'phone',
        'email',
        'logo_url',
        'field_of_business',
        'is_active'
    ];

    public function companyAddresses()
    {
        return $this->hasMany(CompanyAddress::class);
    }

    public function companyMembers()
    {
        return $this->hasMany(CompanyMember::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
