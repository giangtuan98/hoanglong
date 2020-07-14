<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\RouteRepository;
use App\Contracts\Repositories\TripDepartDateRepository;
use App\Contracts\Repositories\TripRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripDepartDateController extends Controller
{
    protected $tripDepartDateRepository;
    protected $tripRepository;
    protected $routeRepository;

    public function __construct(
        TripDepartDateRepository $tripDepartDateRepository,
        TripRepository $tripRepository,
        RouteRepository $routeRepository
    ) {
        $this->tripDepartDateRepository = $tripDepartDateRepository;
        $this->tripRepository = $tripRepository;
        $this->routeRepository = $routeRepository;
    }

    public function index()
    {
        $this->authorize('trip.viewAny');

        $fromDate = Carbon::now()->toDateString();
        // $toDate = Carbon::now()->addDays('7')->toDateString();
        $toDate = $fromDate;
        $search = [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];
        $tripDepartDates = $this->tripDepartDateRepository->search($search);
        $routes = $this->routeRepository->allByAdmin();
        $tripsByRoute = [];

        return view('admin.schedule.index', [
            'tripDepartDates' => $tripDepartDates,
            'routes' => $routes,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'tripsByRoute' => $tripsByRoute,
        ]);
    }
    
    public function createSingleSchedule(Request $request)
    {

        $fromDateTimestamp = strtotime($request->fromDate);
        $toDateTimestamp = strtotime($request->toDate);

        $trip = $this->tripRepository->find($request->tripId);

        if (!$trip) {
            return;
        }

        $trip = [
            'brand_id' => $trip->brand_id,
            'trip_id' => $trip->id,
            'available_seats' => $trip->carType->total_seats,
            'seat_map' => $trip->carType->seat_map,
        ];

        $insertData = [];
        for (; $fromDateTimestamp <= $toDateTimestamp; $fromDateTimestamp += 86400) {
            $trip['depart_date'] = date('Y-m-d', $fromDateTimestamp);
            $insertData[] = $trip;
        }

        if (!insertArrayData('trip_depart_dates', $insertData)) {
            return response()->json([
                'status' => 404,
                'data' => [],
                'message' => 'Lịch chạy không hợp lệ hoặc đã tồn tại'
            ]);
        }
        return response()->json([
            'status' => 201,
            'data' => [],
            'message' => 'Thêm lịch chạy thành công'
        ]);
    }

    public function search(Request $request)
    {
        $fromDate = $request->searchFromDate ?? null;
        $toDate = $request->searchToDate ?? null;
        $routeId = $request->searchRouteId;
        $tripId = $request->searchTripId;
        $searchIsActive = $request->searchIsActive;

        $route = $routeId ? $this->routeRepository->find($routeId) : null;
        $tripsByRoute = $route ? $route->trips : null;

        $data = [
            'fromDate' => $fromDate ? date('Y-m-d', strtotime($request->searchFromDate)) : null,
            'toDate' => $toDate ? date('Y-m-d', strtotime($request->searchToDate)) : null,
            'tripId' => $tripId,
            'routeId' => $routeId,
            'searchIsActive' => $searchIsActive
        ];

        $tripDepartDates = $this->tripDepartDateRepository->search($data);

        $routes = $this->routeRepository->allByAdmin();

        return view('admin.schedule.index', [
            'tripDepartDates' => $tripDepartDates,
            'routes' => $routes,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'tripsByRoute' => $tripsByRoute
        ]);
    }

    public function createMultiSchedule(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $routeId = $request->routeId;
        
        if (!$fromDate || !$toDate) {
            return response()->json([
                'status' => 404,
                'message' => 'Xin mời chọn đầy đủ từ ngày - đến ngày'
            ]);
        }

        $fromDateTimestamp = strtotime($fromDate);
        $toDateTimestamp = strtotime($toDate);

        $route = $this->routeRepository->find($routeId);

        if ($route) {
            $trips = $route->trips()->with('carType:id,total_seats,seat_map')->get();
            $insertData = [];
            foreach ($trips as $trip) {
                $newTrip = [
                    'brand_id' => $trip->brand_id,
                    'trip_id' => $trip->id,
                    'available_seats' => $trip->carType->total_seats,
                    'seat_map' => $trip->carType->seat_map,
                ];
                for ($i = $fromDateTimestamp; $i <= $toDateTimestamp; $i += 86400) { 
                    $newTrip['depart_date'] = date('Y-m-d', $i);
                    $insertData[] = $newTrip;
                }
            }
        }
        if (!insertArrayData('trip_depart_dates',$insertData)) {
            return response()->json([
                'status' => 404,
                'data' => [],
                'message' => 'Lịch chạy không hợp lệ hoặc đã tồn tại'
            ]);
        }
        return response()->json([
            'status' => 201,
            'data' => [],
            'message' => 'Thêm lịch chạy thành công'
        ]);
    }

    public function changeStatusSchedule(Request $request)
    {
        $trip = $this->tripRepository->find($request->tripId);
        if ($request->isActive && !$trip->getOriginal('is_active')) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Kích hoạt chuyến không thành công. Chuyến ' . $trip->name . ' hiện đang ngưng kích hoạt!'
            ]);
        }
        // try {
            $data = [
                'fromDate' => date('Y-m-d', strtotime($request->fromDate)),
                'toDate' => date('Y-m-d', strtotime($request->toDate)),
                'is_active' => $request->isActive,
            ];

            if ($request->tripId) {
                $data['listTripId'] = [$request->tripId];
            } else {
                $route = $this->routeRepository->find($request->routeId);
                $data['listTripId'] = $route ? $route->trips()->pluck('id')->toArray() : [];
            }
            // return $data;
            $this->tripDepartDateRepository->changeStatus($data);

        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => 500,
        //         'data' => [],
        //         'message' => $data['is_active'] ? 'Kích hoạt thất bại' : 'Ngưng kích hoạt thất bại'
        //     ]);
        // }
        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => $data['is_active'] ? 'Kích hoạt thành công' : 'Ngưng kích hoạt thành công'
        ]);
    }

    public function test()
    {
        
    }
}
