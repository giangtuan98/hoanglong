<?php
namespace App\Services;

use App\Enums\TicketStatus;
use App\Jobs\CancelTripJob;
use App\Mail\CancelTripMail;
use App\Models\Ticket;
use App\Models\TripDepartDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CancelTripService
{
  public static function cancelTrip($listTripDepartDateId = []) {
    // get tripDepartDate doesn't run and has ticket
    $tdds = TripDepartDate::whereIn('id', $listTripDepartDateId)
    ->has('tickets')
    ->whereHas('tickets', function (Builder $query) {
      $query->where('status', '!=', TicketStatus::getValue('Cancel'));
    })
    ->whereDate('depart_date', '>', date('Y-m-d'))
    ->with([
      'trip:id,name',
      'tickets' => function($q) {
        $q->select(['id', 'trip_depart_date_id', 'passenger_info->email as email', 'code', 'passenger_info->locale as locale']);
        $q->whereNotIn('status', [
          TicketStatus::getValue('Cancel'),
          TicketStatus::getValue('NotRefundYet'),
          TicketStatus::getValue('Refund'),
        ]);
        return $q;
      }
      ])->get();

      foreach ($tdds as $tdd) {
        foreach ($tdd->tickets as $ticket) {
          $data = [
            'to' => $ticket->email,
            'trip' => $tdd->trip->name,
            'locale' => $ticket->locale,
            'code' => $ticket->code,
          ];
          CancelTripJob::dispatch($data);
        }        
      }
      
      $listTDDId = $tdds->pluck("id")->toArray();
      
      DB::transaction(function () use ($listTDDId) {
        self::rollbackTicket($listTDDId);
        self::rollbackTripDepartDateData($listTDDId);
      });
  }
    
  public static function rollbackTicket($listTripDepartDateId)
  {
    
    Ticket::whereIn('trip_depart_date_id', $listTripDepartDateId)
    ->whereIn('status', [TicketStatus::getValue('Paid'), TicketStatus::getValue('Unpaid')])
    ->update(['status' => DB::raw("IF(status = 'unpaid', 'cancel', 'not refund yet')")]);
    // ->update(['status' => DB::raw("IF(status = 'unpaid', 'cancel', IF(status = 'paid', 'not refund yet', status))")]);
    
  }
  
  public static function rollbackTripDepartDateData($listTripDepartDateId)
  {
    return DB::table('trip_depart_dates as tdd')
    ->select('tdd.*')
    ->join('trips as t', 'tdd.trip_id', 't.id')
    ->join('car_types as c', 't.car_type_id', 'c.id')
    ->whereIn('tdd.id', $listTripDepartDateId)
    ->update([
      'tdd.seat_map' => DB::raw('c.seat_map'),
      'tdd.available_seats' => DB::raw('c.total_seats'),
      ]);
  }
}