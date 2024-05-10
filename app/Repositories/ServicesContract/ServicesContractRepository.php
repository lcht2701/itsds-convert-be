<?php

namespace App\Repositories\ServicesContract;

use App\Http\Requests\StoreServicesContractRequest;
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

    public function paginateByContract($contractId, $perPage = 15, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return ServicesContract::where('contract_id', $contractId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }

    public function getAvailableServices($companyId, $orderBy = 'name', $sortBy = 'asc')
    {
        $services = Service::whereIn('id', function ($query) use ($companyId) {
            $query
                ->select('service_id')
                ->from('services_contracts')
                ->where('company_id', $companyId);
        })
            ->orderBy($orderBy, $sortBy)
            ->get();

        return $services;
    }

    public function addAndUpdate(StoreServicesContractRequest $request)
    {
        $contractId = $request['contract_id'];
        $serviceIds = array_map(function ($service) {
            return $service['id'];
        }, $request['services']);

        $existingServices = ServicesContract::where('contract_id', $contractId)->get();
        $existingServiceIds = $existingServices->pluck('service_id')->toArray();

        if (count($serviceIds) > 0) {
            $servicesToRemove = $existingServices->whereNotIn('service_id', $serviceIds);
            ServicesContract::destroy($servicesToRemove->pluck('id')->toArray());

            $serviceIdsToAdd = array_diff($serviceIds, $existingServiceIds);
            $servicesToAdd = [];
            foreach ($serviceIdsToAdd as $serviceId) {
                $servicesToAdd[] = new ServicesContract([
                    'contract_id' => $contractId,
                    'service_id' => $serviceId,
                ]);
            }

            ServicesContract::insert($servicesToAdd);
        } else {
            ServicesContract::where('contract_id', $contractId)->delete();
        }

        return;
    }

    public function delete($id)
    {
        $servicesContract = ServicesContract::findOrFail($id);
        $servicesContract->delete();
    }

    public function find($id)
    {
        return ServicesContract::findOrFail($id);
    }
}
