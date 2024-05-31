<?php

namespace App\Repositories\Assignment;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Assignment;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssignmentRepository implements IAssignmentRepository
{
    public function getTechnicians($columns = ['*'], $orderBy = 'name', $sortBy = 'asc')
    {
        return User::where('role', UserRole::Technician)
            ->orderBy($orderBy, $sortBy)
            ->get($columns);
    }

    public function assign($ticketId)
    {
        //Lay thong tin cac technician hien dang co it phieu nhat trong ngay hom nay
        $technicians = Assignment::whereDate('created_at', Carbon::today())
            ->select('technician_id', DB::raw('count(*) as total'))
            ->groupBy('technician_id')
            ->orderBy('total', 'asc')
            ->get();

        // Kiểm tra nếu không có technician nào trong danh sách
        if ($technicians->isEmpty()) {
            $selectedTechnicianId = User::where('role', UserRole::Technician)->inRandomOrder()->first()->id;
        } else {
            $minCount = $technicians->first()->total;

            // Lọc ra các technician có số lượng phiếu ít nhất
            $leastBusyTechnicians = $technicians->filter(function ($tech) use ($minCount) {
                return $tech->total == $minCount;
            });

            //Random trong danh sách lấy 1 người duy nhất
            $selectedTechnicianId = $leastBusyTechnicians->random()->technician_id;
        }
        //Tạo assignment mới
        $newAssignment = Assignment::create([
            'technician_id' => $selectedTechnicianId,
            'ticket_id' => $ticketId
        ]);

        return $newAssignment;
    }

    public function create(array $data)
    {
        return Assignment::create($data);
    }

    public function delete($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->forceDelete();
    }

    public function findByTicket($ticketId)
    {
        return Assignment::where('ticket_id', $ticketId)->firstOrNew();
    }
}
