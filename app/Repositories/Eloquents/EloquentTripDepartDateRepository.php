<?php

namespace App\Repositories\Eloquents;

use App\Contracts\Repositories\TripDepartDateRepository;
use App\Models\Route;
use App\Models\TripDepartDate;
use App\Services\CancelTripService;

class EloquentTripDepartDateRepository extends EloquentBaseRepository implements TripDepartDateRepository
{
    protected $model;

    public function __construct(
        TripDepartDate $model
    ) {
        $this->model = $model;
    }
    
    public function all()
    {
        return $this->model->get();
    }
    
    public function paginate($items = null)
    {
        return $this->model->orderBy('trip_id', 'ASC')->orderBy('depart_date', 'ASC')->paginate($items);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function search($data = [])
    {
        $where = [];
        if (isset($data['fromDate'])) {
            $where[] = ['depart_date', '>=', $data['fromDate']];
        }
        if (isset($data['toDate'])) {
            $where[] = ['depart_date', '<=', $data['toDate']];
        }
        if (isset($data['tripId'])) {
            $where[] = ['trip_id', '=', $data['tripId']];
        }
        if (isset($data['searchIsActive']) && $data['searchIsActive'] != -1) {
            $where[] = ['is_active', '=', $data['searchIsActive']];
        }
        return $this->model
        ->whereBrandId(getAuthAdminBrandId())
        ->where($where)
        ->where(function($query) use ($data){
            if (isset($data['routeId']) && !isset($data['tripId'])) {
                $route = Route::findOrFail($data['routeId']);
                if ($route) {
                    $tripId = $route->trips()->pluck('id')->toArray();
                    $query->whereIn('trip_id', $tripId);
                }
            }
        })
        ->with('trip:id,name,arrive_time,depart_time')
        ->orderBy('depart_date', 'ASC')
        ->orderBy('trip_id', 'ASC')
        ->get();
    }
    
    public function changeStatus($data = [])
    {
        $trip = $this->model->whereIn('trip_id', $data['listTripId'])
        ->where([
            ['depart_date', '>=', $data['fromDate']],
            ['depart_date', '<=', $data['toDate']],
        ])->get();
        
        $listTDDId = $trip->pluck('id')->toArray();
        CancelTripService::cancelTrip($listTDDId);
        $this->model->whereIn('id', $listTDDId)->update(['is_active' => $data['is_active']]);
    }
}
