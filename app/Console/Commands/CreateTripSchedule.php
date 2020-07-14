<?php

namespace App\Console\Commands;

use App\Jobs\CreateTripScheduleJob;
use App\Models\Brand;
use App\Models\CarType;
use App\Models\Ticket;
use Illuminate\Console\Command;

class CreateTripSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trip:create-monthly-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create schedule monthly for trips';

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
        // $a = Ticket::find(1);
        // $a->user_id = $a->user_id + 1;
        // $a->save();
        $nextMonth = date('m') + 1;
        $currentYear = date('Y');

        $startTime = strtotime( "$currentYear-$nextMonth-01" );
        // $endTime = strtotime( "$currentYear-($nextMonth+1)-01" );
        $endTime = strtotime( "$currentYear-" . ($nextMonth + 1) . "-01" );
        
        $brands = Brand::with(['trips' => function ($query) {
            $query->where('is_active', true);
            $query->select(['id', 'car_type_id' ,'brand_id']);
        }])->get();

        $carTypes = CarType::select('id', 'seat_map', 'total_seats')->get()->keyBy('id')->toArray();

        foreach($brands as $brand) {
            $trips = $brand->trips;
            $dataInsert = [];
            foreach ($trips as $trip) {
                $newTrip = [
                    'brand_id' => $trip->brand_id,
                    'trip_id' => $trip->id,
                    'available_seats' => $carTypes[$trip->car_type_id]['total_seats'],
                    'seat_map' => $carTypes[$trip->car_type_id]['seat_map'],
                ];
                for ($i = $startTime; $i < $endTime; $i+= 86400) {
                    $newTrip['depart_date'] = date('Y-m-d', $i);
                    $dataInsert[] = $newTrip;
                }
            }

            CreateTripScheduleJob::dispatch($dataInsert);            
        }
    }
}
