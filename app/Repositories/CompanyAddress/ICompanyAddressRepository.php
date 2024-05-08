<?php

namespace App\Repositories\CompanyAddress;

interface ICompanyAddressRepository
{
    public function allByCompany($solutionId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
