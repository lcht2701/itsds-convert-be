<?php

namespace App\Repositories\Company;

use App\Models\Company;
use App\Models\CompanyMember;

class CompanyRepository implements ICompanyRepository
{
    public function getSelectList($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::where("is_active", true)->orderBy($orderBy, $sortBy)->get($columns);
    }

    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function paginateByUser($userId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::whereIn('id', CompanyMember::where('member_id', $userId)->pluck('company_id'))
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }


    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update($id, array $data)
    {
        $company = Company::findOrFail($id);
        $company->update($data);
        return $company;
    }

    public function delete($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
    }

    public function find($id)
    {
        return Company::findOrFail($id);
    }
}
