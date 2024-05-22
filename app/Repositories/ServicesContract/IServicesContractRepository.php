<?php

namespace App\Repositories\ServicesContract;

use App\Http\Requests\StoreServicesContractRequest;

interface IServicesContractRepository
{
    public function allByContract($contractId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginateByContract($contractId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function getAvailableServices($companyId, $orderBy = 'created_at', $sortBy = 'desc');

    public function addAndUpdate(StoreServicesContractRequest $request);

    public function delete($id);

    public function find($id);
}
