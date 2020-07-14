<?php

namespace App\Repositories\Eloquents;

use App\Contracts\Repositories\ProvinceRepository;
use App\Contracts\Repositories\RouteRepository;
use App\Models\District;
use App\Models\Place;
use App\Models\Route;
use App\Models\Trip;
use App\Models\TripDepartDate;
use App\Services\CancelTripService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentRouteRepository extends EloquentBaseRepository implements RouteRepository
{
    protected $model;
    protected $provinceRepository;

    public function __construct(
        Route $model,
        EloquentProvinceRepository $eloquentProvinceRepository
    ) {
        $this->model = $model;
        $this->eloquentProvinceRepository = $eloquentProvinceRepository;
    }

    public function all()
    {
        return $this->model->with('departPlace:id,name', 'desPlace:id,name')->get();
    }

    public function allByAdmin()
    {
        $admin = getAuthAdmin();
        return $this->model->whereBrandId($admin->brand_id)->with('departPlace:id,name', 'desPlace:id,name')->get();
    }
    
    public function paginate($items = null)
    {
        return $this->model->orderBy('index', 'ASC')->paginate($items);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findTrips($data)
    {
        $departProvince = $data['departProvince'];
        $departPlaces = $departProvince->places()->pluck('places.id')->toArray();

        $desProvince = $data['desProvince'];
        $desPlaces = $desProvince->places()->pluck('places.id')->toArray();

        $formatDate = date("Y-m-d", strtotime($data['departDate']));

        $now = Carbon::now()->addHours(12)->timestamp;
        if ($now <= strtotime($data['departDate'])) {
            $timeNow = '00:00:00';
        } else {
            $timeNow = Carbon::now()->addHours(12)->format('H:i:00');
        }

        $whereBrandId = isset($data['brand_id']) ? [['b.id', $data['brand_id']]] : [];

        $routes = DB::table('routes as r')
        ->select(
            'r.id as routeId',
            'r.name as routeName',
            'r.distance',
            'r.duration',
            'r.price',
            't.id as tripId',
            't.name as tripName',
            't.depart_time as departTime',
            't.arrive_time as arriveTime',
            't.pick_up_schedule as pickUpSchedule',
            // 't.get_off_schedule as getOffSchedule',
            'ct.name as carType',
            'tdd.available_seats as availableSeats',
            'tdd.seat_map as seatMap',
            'tdd.id as tripDepartDateId',
            'b.name as brandName',
            'b.id as brandId',
            'p1.name as departName',
            'p1.id as departId',
            'p2.name as desName',
            'p2.id as desId'
        )
        ->where($whereBrandId)
        ->whereIn('depart_place_id', $departPlaces)
        ->whereIn('des_place_id', $desPlaces)
        ->join('trips as t', 'r.id', 't.route_id')
        ->join('car_types as ct', 't.car_type_id', 'ct.id')
        ->join('trip_depart_dates as tdd', 't.id', 'tdd.trip_id')
        ->join('brands as b', 'r.brand_id', 'b.id')
        ->join('places as p1', 'r.depart_place_id', 'p1.id')
        ->join('places as p2', 'r.des_place_id', 'p2.id')
        ->where([
            ['tdd.depart_date', $formatDate],
            ['tdd.available_seats', '>=',$data['quantity']],
            ['t.depart_time', '>=', $timeNow],
            ['tdd.is_active', '=', true],
            ['t.is_active', '=', true],
            ['r.is_active', '=', true],
            ['b.is_active', '=', true]
            ])
        ->get();
        return $routes;
    }

    public function checkRouteExist($departPlaceId, $desPlaceId)
    {
        $count = $this->model->where([
            ['depart_place_id', $departPlaceId],
            ['des_place_id', $desPlaceId],
        ])->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function toggleIsActive(Route $route, $isActive)
    {
        try {
            DB::transaction(function () use ($route, $isActive) {
                $listTripId = $route->trips()->pluck('id')->toArray();
                Trip::whereIn('id', $listTripId)->update(['is_active' => $isActive]);
                TripDepartDate::whereIn('trip_id', $listTripId)->update(['is_active' => $isActive]);
                $listTDDId = TripDepartDate::whereIn('trip_id', $listTripId)->pluck('id')->toArray();
                CancelTripService::cancelTrip($listTDDId);
                $route->is_active = $isActive;
                $route->save();
            });
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
}
