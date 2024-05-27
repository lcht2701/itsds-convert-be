<?php

namespace App\Repositories\CompanyMember;

use App\Enums\UserRole;
use App\Models\CompanyMember;
use App\Models\User;

class CompanyMemberRepository implements ICompanyMemberRepository
{
    public function allByCompany($companyId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return CompanyMember::where('company_id', $companyId)
            ->orderBy($orderBy, $sortBy)
            ->get($columns);
    }

    public function paginateByCompany($companyId, $perPage = 10, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc')
    {
        return CompanyMember::where('company_id', $companyId)
            ->orderBy($orderBy, $sortBy)
            ->paginate($perPage, $columns);
    }

    public function getCompanyAdmins($companyId)
    {
        return CompanyMember::where('company_id', $companyId)
            ->whereHas('member', function ($query) {
                $query->where('role', UserRole::CompanyAdmin);
            })->get();
    }

    public function getMembersNotInCompany($companyId)
    {
        $memberIds = CompanyMember::where('company_id', $companyId)->pluck('member_id');
        return User::whereIn('role', [UserRole::Customer, UserRole::CompanyAdmin])
            ->whereNotIn("id", $memberIds)
            ->get();
    }

    public function create(array $data)
    {
        return CompanyMember::create($data);
    }

    public function update($id, array $data)
    {
        $address = CompanyMember::findOrFail($id);
        $address->update($data);
        return $address;
    }

    public function delete($id)
    {
        $address = CompanyMember::findOrFail($id);
        $address->forceDelete();
    }

    public function find($id)
    {
        return CompanyMember::findOrFail($id);
    }
}
