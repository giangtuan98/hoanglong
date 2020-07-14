<?php

namespace App\Repositories\Eloquents;

use App\Contracts\Repositories\TripRepository;
use App\Models\Trip;
use App\Models\TripDepartDate;
use App\Services\CancelTicketService;
use App\Services\CancelTripService;
use Illuminate\Support\Facades\DB;

class EloquentTripRepository extends EloquentBaseRepository implements TripRepository
{
    protected $model;
    protected $provinceRepository;

    public function __construct(
        Trip $model,
        EloquentProvinceRepository $eloquentProvinceRepository
    ) {
        $this->model = $model;
        $this->eloquentProvinceRepository = $eloquentProvinceRepository;
    }

    public function all()
    {
        return $this->model->get();
    }

    public function allByAdmin()
    {
        $admin = getAuthAdmin();
        return $this->model->whereBrandId($admin->brand_id)->get();
    }
    
    public function paginate($items = null)
    {
        return $this->model->orderBy('index', 'ASC')->paginate($items);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function toggleIsActive(Trip $trip, $isActive)
    {
        try {
            $listTripDepartDateId = $trip->tripDepartDates()->pluck('id')->toArray();
            CancelTripService::cancelTrip($listTripDepartDateId);

            DB::transaction(function () use ($trip, $isActive) {
                TripDepartDate::where('trip_id', '=', $trip->id)->update(['is_active' => $isActive]);
                
                $trip->is_active = $isActive;
                $trip->save();
            });
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
}
