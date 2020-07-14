<?php

namespace App\Repositories\Eloquents;

use App\Contracts\Repositories\TicketRepository;
use App\Enums\TicketStatus;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\TripDepartDate;
use Illuminate\Support\Facades\DB;

class EloquentTicketRepository extends EloquentBaseRepository implements TicketRepository
{
    protected $model;
    protected $routeModel;

    public function __construct(
        Ticket $model,
        Route $routeModel
    ) {
        $this->model = $model;
        $this->routeModel = $routeModel;
    }

    public function all()
    {
        return $this->model->with('images')->orderBy('index', 'ASC')->get();
    }
    
    public function paginate($items = null)
    {
        return $this->model->orderBy('index', 'ASC')->paginate($items);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function createTicket($data)
    {
        $passengerInfo = [
            'name' => $data['passengerName'],
            'phone' => $data['passengerPhone'],
            'email' => $data['passengerEmail'],
            'address' => $data['passengerAddress'],
            'locale' => app()->getLocale()
        ];

        $trip_info = [
            'unit_price' => $data['price'],
            'route_name' => $data['routeName'],
            'pickup_place' => $data['pickupPlaceName'],
            'pickup_time' => $data['pickupTime'],
            'pickup_url' => $data['pickupPlaceUrl'],
            'dep_province_name' => $data['depProvinceName'],
            'des_province_name' => $data['desProvinceName'],
            'depart_name' => $data['departName'],
            'depart_time' => $data['departTime'],
            'des_name' => $data['desName'],
            'des_time' => $data['desTime'],
            'trip_name' => $data['tripName']
        ];
        
        $selectedSeats = explode(',', $data['selectedSeats']);
        $code = $this->generateRandomCode(11);
        $ticket = [
            'trip_depart_date_id' => $data['tddId'],
            'quantity' => $data['quantity'],
            'total' => $data['price'] * $data['quantity'],
            'brand_id' => $data['brandId'],
            'user_id' => $data['userId'],
            'passenger_info' => json_encode($passengerInfo),
            'list_seat' => json_encode($selectedSeats),
            'code' => $code,
            'trip_info' => json_encode($trip_info)
        ];
        return $this->model->create($ticket);
    }

    public function generateRandomCode($length)
    {
        $code = '';
        do {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $count = $this->model->where('code', $code)->count();
        } while ($count >  0);
        return $code;
    }

    public function allByAdmin()
    {
        return $this->model->whereBrandId(getAuthAdminBrandId())->get();
    }

    public function searchByAdmin($data)
    {
        $where = [];
        $listTripId = [];

        if (isset($data['route_id']) && !isset($data['trip_id'])) {
            $route = $this->routeModel->findOrFail($data['route_id']);
            $listTripId = $route->trips()->pluck('id')->toArray();
        }

        if (isset($data['trip_id'])) {
            $listTripId = [$data['trip_id']];
        }

        // if (isset($data['status'])) {
        //     $where[] = ['status', $data['status']];
        // }

        if (isset($data['code'])) {
            $where[] = ['code', $data['code']];
        }

        $tickets = $this->model
        // ->with('tripDepartDate:id,depart_date')
        ->whereHas('tripDepartDate', function ($q) use ($listTripId) {
            if (count($listTripId)) {
                $q->whereIn('trip_id', $listTripId);
            }
        })
        ->where($where)
        ->where(function ($q) use ($data) {
            isset($data['from_date']) && $q->whereDate('created_at', '>=', $data['from_date']);
            isset($data['to_date']) && $q->whereDate('created_at', '<=', $data['to_date']);
            if (isset($data['status'])) {
                $data['status'] == TicketStatus::getValue('Booked') ? $q->whereIn('status', [TicketStatus::getValue('Unpaid'), TicketStatus::getValue('Paid')]) : $q->where('status', $data['status']);
            }
        })
        ->where('brand_id', getAuthAdminBrandId())
        ->with('tripDepartDate:id,depart_date')
        ->get();
        return $tickets;
    }

    public function findByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function changeStatus($code, $status)
    {
        return $this->model->where('code', $code)->update(['status' => $status]);
    }

    public function rollback($ticketCode)
    {
        $ticket = $this->model->where('code', $ticketCode)->firstOrFail();
        $listSeat = json_decode($ticket->list_seat);

        $tripDepartDate = $ticket->tripDepartDate;
        $seatMap = json_decode($tripDepartDate->seat_map, true);
        
        foreach ($listSeat as $index) {
            $seatMap[$index] = 0;
        }
        $tripDepartDate->seat_map = json_encode($seatMap);
        $tripDepartDate->available_seats = $tripDepartDate->available_seats + count($listSeat);
        return $tripDepartDate->save();
    }

    public function getTicketByDate($date)
    {
        return $this->model->whereDate('created_at', $date)->brand()->isBooked()->get();
    }

    public function getTicketByMonth($month)
    {
        return $this->model->whereMonth('created_at', $month)->brand()->isBooked()->get();
    }
}
