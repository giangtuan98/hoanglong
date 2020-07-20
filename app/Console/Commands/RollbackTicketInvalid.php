<?php

namespace App\Console\Commands;

use App\Enums\TicketStatus;
use App\Services\CancelInvalidTicketService;
use App\Services\CancelTripService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RollbackTicketInvalid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:rollback-invalid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback các vé trả sau nhưng chưa thanh toán trước 6 tiếng';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Tìm các vé unpaid trước 6h và rollback
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:00');
        $timestampExpired = Carbon::now()->addHours(config('constants.UNPAID_TICKET_HOUR_LIMIT'))->timestamp;
        $dateExpire = date('Y-m-d', $timestampExpired);
        $timeExpire = date('H:i:00', $timestampExpired);
        // Lay danh sách ve khong hop len => dua vao job de huy ve + rollback ve
        $listInvalidTicketId = DB::table('tickets')
        ->join('trip_depart_dates as tdd', 'tickets.trip_depart_date_id', '=', 'tdd.id')
        ->join('trips as t', 'tdd.trip_id', '=', 't.id')
        ->where([
            ["status", TicketStatus::getValue('Unpaid')],
        ])->where(function ($query) use ($currentDate, $currentTime, $dateExpire, $timeExpire) {
            $query->where([
                ['tdd.depart_date', '=', $currentDate],
                ['t.depart_time', '>', $currentTime],
            ]);
            $query->orWhere([
                ['tdd.depart_date', '>', $currentDate],
                ['t.depart_time', '>', '00:00:00'],
            ]);
        })->where(function ($query) use ($currentDate, $currentTime, $dateExpire, $timeExpire) {
            $query->where([
                ['tdd.depart_date', '=', $dateExpire],
                ['t.depart_time', '<', $timeExpire],
            ]);
            $query->orWhere([
                ['tdd.depart_date', '<', $dateExpire],
            ]);
        })
        ->pluck('tickets.id')
        ->toArray();
        // Huy ve 
        // tao service rollback ve -> goi den repository + job + gui mail huy ve
        CancelInvalidTicketService::cancelTicket($listInvalidTicketId);
    }
}
