<?php

namespace App\Repositories\Company;

use App\Models\Company;

class CompanyRepository implements ICompanyRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Company::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
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
