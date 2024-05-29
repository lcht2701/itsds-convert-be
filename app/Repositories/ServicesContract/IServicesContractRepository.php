<?php

namespace App\Repositories\ServicesContract;

interface IServicesContractRepository
{
    public function allByContract($contractId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginateByContract($contractId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function getSelectList($contractId, $orderBy = 'name', $sortBy = 'asc');

    public function add($contractId, array $request);

    public function delete($id);

    public function find($id);
}
