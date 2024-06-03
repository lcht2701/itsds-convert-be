<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContractStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use App\Models\User;
use App\Repositories\Contract\IContractRepository;
use App\Repositories\Dashboard\IDashboardRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    protected $dashboardRepository;

    public function __construct(IDashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function customerDashboard(User $user)
    {
        $result = $this->dashboardRepository->GetCustomerDashboard($user->id);
        return $this->sendResponse("Get Customer Dashboard", 200, $result);
    }

    public function companyAdminDashboard(User $user)
    {
        $result = $this->dashboardRepository->GetCompanyAdminDashboard($user->id);
        return $this->sendResponse("Get Company Admin Dashboard", 200, $result);
    }

    public function technicianDashboard(User $user)
    {
        $result = $this->dashboardRepository->GetTechnicianDashboard($user->id);
        return $this->sendResponse("Get Technician Dashboard", 200, $result);
    }

    public function managerDashboard()
    {
        $result = $this->dashboardRepository->GetManagerDashboard();
        return $this->sendResponse("Get Manager Dashboard", 200, $result);
    }
}
