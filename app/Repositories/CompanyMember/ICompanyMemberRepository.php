<?php

namespace App\Repositories\CompanyMember;

interface ICompanyMemberRepository
{
    public function allByCompany($companyId, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function paginateByCompany($companyId, $perPage = 15, $columns = ['*'], $orderBy = 'created_at', $sortBy = 'desc');

    public function getCompanyAdmins($companyId);

    public function getMembersNotInCompany($companyId);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id);
}
