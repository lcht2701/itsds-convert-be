<?php

namespace App\Repositories\Dashboard;

interface IDashboardRepository
{
    public function GetCustomerDashboard($userId);
    public function GetCompanyAdminDashboard($userId);
    public function GetTechnicianDashboard($userId);
    public function GetManagerDashboard();
}
