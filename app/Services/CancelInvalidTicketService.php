<?php
namespace App\Services;

use App\Contracts\Repositories\TicketRepository;
use App\Enums\TicketStatus;
use App\Jobs\CancelInvalidTicketJob;
use App\Jobs\CancelTripJob;
use App\Mail\CancelInvalideTicketMail;
use App\Mail\CancelTripMail;
use App\Models\Ticket;
use App\Models\TripDepartDate;
use App\Repositories\Eloquents\EloquentTicketRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CancelInvalidTicketService
{
    protected static $ticketRepository;

    public function __construct(TicketRepository $ticketRepository) {
        $this->ticketRepository = $ticketRepository;
    }

    public static function cancelTicket($listInvalidTicket = []) {
        // get tripDepartDate doesn't run and has ticket
        $tickets = Ticket::whereIn('id', $listInvalidTicket)->get();
        // dd($tickets);
        foreach ($tickets as $ticket) {
            $data = [
                'to' => $ticket->passenger_info['email'],
                'trip' => $ticket->trip_info['trip_name'],
                'locale' => $ticket->passenger_info['locale'],
                'code' => $ticket->code,
            ];
            CancelInvalidTicketJob::dispatch($data);
            // $data['view'] = 'mail.cancel_invalid_ticket';
            // $data['subject'] = __('Cancel invalid ticket!');
            // Mail::to($data['to'])->send(new CancelInvalideTicketMail($data));
        }
        $listInvalidTicketCode = $tickets->pluck('code')->toArray();
        self::rollbackTicket($listInvalidTicketCode);
    }
    
    public static function rollbackTicket($listInvalidTicketCode)
    {
        foreach ($listInvalidTicketCode as $ticketCode) {
            self::rollback($ticketCode);
        }
    }

    public static function rollback($ticketCode)
    {
        try {
            $ticket = Ticket::where('code', $ticketCode)->firstOrFail();
            $listSeat = json_decode($ticket->list_seat);

            $tripDepartDate = $ticket->tripDepartDate;
            $seatMap = json_decode($tripDepartDate->seat_map, true);
            
            foreach ($listSeat as $index) {
                $seatMap[$index] = 0;
            }
            $tripDepartDate->seat_map = json_encode($seatMap);
            $tripDepartDate->available_seats = $tripDepartDate->available_seats + count($listSeat);
            DB::beginTransaction();
            $tripDepartDate->save();
            $ticket->status = TicketStatus::getValue('Cancel');
            $ticket->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        
    }
}