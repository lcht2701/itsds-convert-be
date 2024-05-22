<?php

namespace App\Repositories\Contract;

use App\Enums\ContractStatus;
use App\Models\Contract;
use Carbon\Carbon;

class ContractRepository implements IContractRepository
{
    public function all($columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Contract::orderBy($orderBy, $sortBy)->get($columns);
    }

    public function paginate($perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return Contract::orderBy($orderBy, $sortBy)->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return Contract::create($data);
    }

    public function update($id, array $data)
    {
        $contract = Contract::findOrFail($id);
        $contract->update($data);
        return $contract;
    }

    public function delete($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();
    }

    public function find($id)
    {
        return Contract::findOrFail($id);
    }

    public function setStatus($startDate, $duration)
    {
        $today = Carbon::today();
        $startDate = Carbon::createFromDate('Y-m-d', $startDate);
        $endDate = $startDate->copy()->addMonths($duration);

        if ($today < $startDate) {
            $status = ContractStatus::Pending;
        } else if ($today > $endDate) {
            $status = ContractStatus::Expired;
        } else {
            $status = ContractStatus::Active;
        }

        return $status;
    }
}
