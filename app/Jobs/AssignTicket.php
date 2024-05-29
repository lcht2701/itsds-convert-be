<?php

namespace App\Jobs;

use App\Enums\TicketStatus;
use App\Models\Assignment;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AssignTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    /**
     * Create a new job instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //Lay thong tin cac technician hien dang co it phieu nhat trong ngay hom nay
        $technicians = Assignment::whereDate('created_at', Carbon::today())
            ->select('technician_id', DB::raw('count(*) as total'))
            ->groupBy('technician_id')
            ->orderBy('total', 'asc')
            ->get();

        // Kiểm tra nếu không có technician nào trong danh sách
        if ($technicians->isEmpty()) {
            throw new BadRequestException("There's no technicians in the system. Please assign a technician to start ticket assignment process");
        }

        $minCount = $technicians->first()->total;

        // Lọc ra các technician có số lượng phiếu ít nhất
        $leastBusyTechnicians = $technicians->filter(function ($tech) use ($minCount) {
            return $tech->total == $minCount;
        });

        //Random trong danh sách lấy 1 người duy nhất
        $selectedTechnicianId = $leastBusyTechnicians->random()->technician_id;


        //Tạo assignment mới
        $newAssignment = Assignment::create([
            'technician_id' => $selectedTechnicianId,
            'ticket_id' => $this->ticket->id
        ]);

        //Cập nhật trạng thái của ticket sau khi tạo assignment
        if ($newAssignment) {
            $this->ticket->ticketStatus = TicketStatus::Assigned;
            $this->ticket->save();
        }
    }
}
