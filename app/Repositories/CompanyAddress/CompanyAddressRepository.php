<?php

namespace App\Repositories\CompanyAddress;

use App\Models\CompanyAddress;

class CompanyAddressRepository implements ICompanyAddressRepository
{
    public function paginateByCompany($companyId, $perPage = 5, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return CompanyAddress::where('company_id', $companyId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }


    public function create(array $data)
    {
        return CompanyAddress::create($data);
    }

    public function update($id, array $data)
    {
        $address = CompanyAddress::findOrFail($id);
        $address->update($data);
        return $address;
    }

    public function delete($id)
    {
        $address = CompanyAddress::findOrFail($id);
        $address->delete();
    }

    public function find($id)
    {
        return CompanyAddress::findOrFail($id);
    }
}
