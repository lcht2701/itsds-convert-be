<?php

namespace App\Repositories\ServicesContract;

use App\Http\Requests\StoreServicesContractRequest;
use App\Models\Contract;
use App\Models\Service;
use App\Models\ServicesContract;

class ServicesContractRepository implements IServicesContractRepository
{
    public function allByContract($contractId, $columns = ['*'], $orderBy = 'id', $sortBy = 'asc')
    {
        return ServicesContract::where('contract_id', $contractId)
            ->orderBy($orderBy, $sortBy)
            ->get($columns);
    }

    public function paginateByContract($contractId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return ServicesContract::where('contract_id', $contractId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }

    public function getAvailableServices($contractId, $orderBy = 'name', $sortBy = 'asc')
    {
        $services = Service::whereIn('id', function ($query) use ($contractId) {
            $query
                ->select('service_id')
                ->from('services_contracts')
                ->where('contract_id', $contractId);
        })
            ->orderBy($orderBy, $sortBy)
            ->get();

        return $services;
    }

    public function getSelectList($contractId, $orderBy = 'name', $sortBy = 'asc')
    {
        $services = Service::whereNotIn('id', function ($query) use ($contractId) {
            $query
                ->select('service_id')
                ->from('services_contracts')
                ->where('contract_id', $contractId);
        })
            ->orderBy($orderBy, $sortBy)
            ->get();

        return $services;
    }

    public function add($contractId, array $request)
    {
        $serviceIds = $request['serviceIds'];
        $servicesToAdd = [];

        if (count($serviceIds) > 0) {

            foreach ($serviceIds as $serviceId) {
                $newService = ServicesContract::create([
                    'contract_id' => $contractId,
                    'service_id' => $serviceId,
                ]);
                array_push($servicesToAdd, $newService);
            }
        }
        return $servicesToAdd;
    }


    public function delete($id)
    {
        $servicesContract = ServicesContract::findOrFail($id);
        $servicesContract->forceDelete();
    }

    public function find($id)
    {
        return ServicesContract::findOrFail($id);
    }
}
