<?php

namespace App\Repositories\Dashboard;

use App\Enums\ContractStatus;
use App\Enums\TicketStatus;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketSolutionResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\CompanyMember;
use App\Models\Contract;
use App\Models\Reaction;
use App\Models\Ticket;
use App\Models\TicketSolution;
use App\Models\User;
use Illuminate\Support\Number;

class DashboardRepository implements IDashboardRepository
{

    public function GetCustomerDashboard($userId)
    {
        // TODO: Implement GetCustomerTicketDashboard() method.
        $num_new_tickets = Ticket::where('requester_id', $userId)
            ->whereDate('created_at', today())
            ->count() || 0;
        $num_progressing_tickets = Ticket::where('requester_id', $userId)
            ->whereNotIn('ticketStatus', [TicketStatus::Closed, TicketStatus::Cancelled])
            ->count() || 0;
        $total_completed_tickets = Ticket::where('requester_id', $userId)
            ->where('ticketStatus', TicketStatus::Closed)
            ->count() || 0;
        $total_cancel_tickets = Ticket::where('requester_id', $userId)
            ->where('ticketStatus', TicketStatus::Cancelled)
            ->count() || 0;
        $count_tickets_month = Ticket::where('requester_id', $userId)
            ->whereMonth('created_at', today()->month)
            ->count() || 0;
        $count_tickets_last_month = Ticket::where('requester_id', $userId)
            ->whereMonth('created_at', today()->subMonthNoOverflow()->month)
            ->count() || 0;
        $count_tickets_month_percentage = Number::percentage($count_tickets_month / $count_tickets_last_month * 100 - 100, 1);
        $recent_tickets = Ticket::where('requester_id', $userId)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        $recent_ticket_solutions = TicketSolution::orderByDesc('created_at')->take(5)->get();

        $result = [
            'num_new_tickets' => $num_new_tickets,
            'num_progressing_tickets' => $num_progressing_tickets,
            'total_completed_tickets' => $total_completed_tickets,
            'total_cancel_tickets' => $total_cancel_tickets,
            'count_tickets_month' => $count_tickets_month,
            'count_tickets_month_percentage' => $count_tickets_month_percentage,
            'recent_tickets' => TicketResource::collection($recent_tickets),
            'recent_ticket_solutions' => TicketSolutionResource::collection($recent_ticket_solutions),
        ];

        return $result;
    }

    public function GetCompanyAdminDashboard($userId)
    {
        // TODO: Implement GetCustomerTicketDashboard() method.
        $companyId = CompanyMember::where('member_id', $userId)->firstOrFail()->company_id;
        $num_company_new_tickets =
            Ticket::whereIn('requester_id', function ($query) use ($companyId) {
                $query
                    ->select('member_id')
                    ->from('company_members')
                    ->where('company_id', $companyId);
            })
                ->count() || 0;
        $num_company_progressing_tickets =
            Ticket::whereIn('requester_id', function ($query) use ($companyId) {
                $query
                    ->select('member_id')
                    ->from('company_members')
                    ->where('company_id', $companyId);
            })
                ->whereNotIn('ticketStatus', [TicketStatus::Closed, TicketStatus::Cancelled])
                ->count() || 0;
        $total_company_completed_tickets =
            Ticket::whereIn('requester_id', function ($query) use ($companyId) {
                $query
                    ->select('member_id')
                    ->from('company_members')
                    ->where('company_id', $companyId);
            })
                ->where('ticketStatus', TicketStatus::Closed)
                ->count() || 0;
        $total_company_cancelled_tickets =
            Ticket::whereIn('requester_id', function ($query) use ($companyId) {
                $query
                    ->select('member_id')
                    ->from('company_members')
                    ->where('company_id', $companyId);
            })
                ->where('ticketStatus', TicketStatus::Cancelled)
                ->count() || 0;
        $company_members = CompanyMember::where('company_id', $companyId)->count();
        $active_contracts = Contract::where('company_id', $companyId)
            ->where('status', ContractStatus::Active)
            ->count() || 0;
        $recent_company_tickets =
            Ticket::whereIn('requester_id', function ($query) use ($companyId) {
                $query
                    ->select('member_id')
                    ->from('company_members')
                    ->where('company_id', $companyId);
            })
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
        $recent_ticket_solutions = TicketSolution::orderByDesc('created_at')->take(5)->get();

        $result = [
            'num_company_new_tickets' => $num_company_new_tickets,
            'num_company_progressing_tickets' => $num_company_progressing_tickets,
            'total_company_completed_tickets' => $total_company_completed_tickets,
            'total_company_cancelled_tickets' => $total_company_cancelled_tickets,
            'company_members' => $company_members,
            'active_contracts' => $active_contracts,
            'recent_company_tickets' => TicketResource::collection($recent_company_tickets),
            'recent_ticket_solutions' => TicketSolutionResource::collection($recent_ticket_solutions),
        ];
        return $result;
    }

    public function GetTechnicianDashboard($userId)
    {
        // TODO: Implement GetTechnicianTicketDashboard() method.
        // Get Tickets By Status
        $current_assigned_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->where('ticketStatus', TicketStatus::Assigned)
            ->count() || 0;
        $current_progressing_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->whereIn('ticketStatus', [TicketStatus::InProgress, TicketStatus::Resolved])
            ->count() || 0;
        $total_completed_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->where('ticketStatus', TicketStatus::Closed)
            ->count() || 0;
        $total_cancel_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->where('ticketStatus', TicketStatus::Cancelled)
            ->count() || 0;

        $new_tickets_current_month = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->whereMonth('created_at', today()->month)
            ->count() || 0;
        $new_tickets_last_month = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->whereMonth('created_at', today()->subMonthNoOverflow()->month)
            ->count() || 0;
        $percentage_tickets_month = Number::percentage($new_tickets_current_month / $new_tickets_last_month * 100 - 100, 1);

        $num_solutions_owned = TicketSolution::where('owner_id', $userId)
            ->count();
        $num_reactions_today = Reaction::whereDate('created_at', today())
            ->whereIn('ticket_solution_id', function ($query) use ($userId) {
                $query
                    ->select('id')
                    ->from('ticket_solutions')
                    ->where('owner_id', $userId);
            })
            ->count() || 0;
        $num_comments_today = Comment::whereDate('created_at', today())
            ->whereIn('ticket_solution_id', function ($query) use ($userId) {
                $query
                    ->select('id')
                    ->from('ticket_solutions')
                    ->where('owner_id', $userId);
            })
            ->count() || 0;

        $available_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        $recent_solved_tickets = Ticket::whereIn('id', function ($query) use ($userId) {
            $query
                ->select('ticket_id')
                ->from('assignments')
                ->where('technician_id', $userId);
        })
            ->where('ticketStatus', TicketStatus::Closed)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $result = [
            'current_assigned_tickets' => $current_assigned_tickets,
            'current_progressing_tickets' => $current_progressing_tickets,
            'total_completed_tickets' => $total_completed_tickets,
            'total_cancel_tickets' => $total_cancel_tickets,
            'new_tickets_month' => $new_tickets_current_month,
            'new_tickets_month_percentage' => $percentage_tickets_month,
            'num_solutions_owned' => $num_solutions_owned,
            'num_actions_today' => ($num_reactions_today + $num_comments_today),
            'available_tickets' => TicketResource::collection($available_tickets),
            'recent_solved_tickets' => TicketResource::collection($recent_solved_tickets),
        ];
        return $result;
    }

    public function GetManagerDashboard()
    {
        // Fetch counts for the current month
        $count_tickets_current_month = Ticket::whereMonth('created_at', today()->month)->count() || 0;
        $count_solutions_current_month = TicketSolution::whereMonth('created_at', today()->month)->count() || 0;
        $count_contracts_current_month = Contract::whereMonth('created_at', today()->month)->count() || 0;
        $count_active_users_current_month = User::whereMonth('created_at', today()->month)->count() || 0;

        // Fetch counts for the previous month
        $count_tickets_last_month = Ticket::whereMonth('created_at', today()->subMonthNoOverflow()->month)->count() || 0;
        $count_solutions_last_month = TicketSolution::whereMonth('created_at', today()->subMonthNoOverflow()->month)->count() || 0;
        $count_contracts_last_month = Contract::whereMonth('created_at', today()->subMonthNoOverflow()->month)->count() || 0;
        $count_active_users_last_month = User::whereMonth('created_at', today()->subMonthNoOverflow()->month)->count() || 0;

        // Calculate percentages, handling division by zero
        $percentage_tickets_month = Number::percentage($count_tickets_current_month / $count_tickets_last_month * 100 - 100, 1) || 0;
        $percentage_solutions_month = Number::percentage($count_solutions_current_month / $count_solutions_last_month * 100 - 100, 1) || 0;
        $percentage_contracts_month = Number::percentage($count_contracts_current_month / $count_contracts_last_month * 100 - 100, 1) || 0;
        $percentage_active_users_month = Number::percentage($count_active_users_current_month / $count_active_users_last_month * 100 - 100, 1) || 0;

        // Fetch recent tickets and new users
        $recent_tickets = Ticket::orderByDesc('created_at')->take(5)->get();
        $recent_new_users = User::orderByDesc('created_at')->take(5)->get();

        // Prepare the result array
        $result = [
            "new_tickets_current_month" => $count_tickets_current_month,
            "new_tickets_percentage" => $percentage_tickets_month,
            'new_solutions_current_month' => $count_solutions_current_month,
            'new_solutions_percentage' => $percentage_solutions_month,
            'new_contracts_current_month' => $count_contracts_current_month,
            'new_contracts_percentage' => $percentage_contracts_month,
            'new_active_users_current_month' => $count_active_users_current_month,
            'new_active_users_percentage' => $percentage_active_users_month,
            'recent_tickets' => TicketResource::collection($recent_tickets),
            'recent_new_users' => UserResource::collection($recent_new_users),
        ];

        return $result;
    }
}
