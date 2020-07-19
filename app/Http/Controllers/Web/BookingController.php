<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\BrandRepository;
use App\Contracts\Repositories\ProvinceRepository;
use App\Contracts\Repositories\RouteRepository;
use App\Contracts\Repositories\TicketRepository;
use App\Contracts\Repositories\TripDepartDateRepository;
use App\Contracts\Repositories\UserRepository;
use App\Enums\TicketStatus;
use App\Events\NewTicketEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\CancelTicketJob;
use App\Jobs\SendMailJob;
use App\Mail\CancelTicketMail;
use App\Mail\SendMail;
use App\Models\Brand;
use App\Models\Route;
use App\Models\Ticket;
use App\Notifications\NewTicketNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Pusher\Pusher;

class BookingController extends Controller
{
    protected $routeRepository;
    protected $provinceRepository;
    protected $userRepository;
    protected $ticketRepository;
    protected $tripDepartDateRepository;
    protected $brandRepository;

    public function __construct(
        RouteRepository $routeRepository,
        ProvinceRepository $provinceRepository,
        UserRepository $userRepository,
        TicketRepository $ticketRepository,
        TripDepartDateRepository $tripDepartDateRepository,
        BrandRepository $brandRepository
    ) {
        $this->routeRepository = $routeRepository;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
        $this->ticketRepository = $ticketRepository;
        $this->tripDepartDateRepository = $tripDepartDateRepository;
        $this->brandRepository = $brandRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('departure_id') && $request->has('destination_id') && $request->has('depart_date') && $request->has('quantity')) {
            $departProvince = $this->provinceRepository->find($request->departure_id);
            $desProvince = $this->provinceRepository->find($request->destination_id);

            $data = [
                'departProvince' => $departProvince,
                'desProvince' => $desProvince,
                'quantity'  => $request->quantity,
                'departDate' => $request->depart_date,
                'brand_id' => $request->brand_id
            ];
            $routes = $this->routeRepository->findTrips($data);
            \Session::put('step', config('constants.step.STEP1'));
            return view(
                'web.booking.index',
                [
                'routes' => $routes,
                'departProvince' => $departProvince,
                'desProvince' => $desProvince,
            ]
            );
        }
        \Session::put('step', config('constants.step.STEP0'));
        return view('web.booking.index');
    }

    public function search(Request $request)
    {
        $data = [
            'depart_province_id' => $request->departure_id,
            'des_province_id' => $request->destination_id,
            'quantity'  => $request->quantity,
            'depart_date' => $request->depart_date,
            'brand_id' => $request->brand_id
        ];
        $routes = $this->routeRepository->findTrips($data);

        return $routes;
    }

    public function bookRoute(Request $request)
    {
        $formData = $request->formData;
        // $formData = "passengerName=a&passengerPhone=a&passengerEmail=a%40gmail.com&passengerAddress=a&price=80000&quantity=2&date=30-05-2020&paymenttype=1&tddId=19&brandId=11&departName=B%E1%BA%BFn+xe+H%C3%A0+N%E1%BB%99i&departTime=08%3A30%3A00&desName=B%E1%BA%BFn+xe+Th%C3%A1i+B%C3%ACnh&desTime=11%3A00%3A00&routeName=Tuy%E1%BA%BFn+H%C3%A0+N%E1%BB%99i+-+Th%C3%A1i+B%C3%ACnh+(B%E1%BA%BFn+xe+H%C3%A0+N%E1%BB%99i+-+B%E1%BA%BFn+xe+Th%C3%A1i+B%C3%ACnh)&depProvinceName=H%C3%A0+N%E1%BB%99i&desProvinceName=Th%C3%A1i+B%C3%ACnh&selectedSeats=10%2C11&pickupPlace=101&pickupPlaceName=B%E1%BA%BFn+xe+H%C3%A0+N%E1%BB%99i&pickupTime=08%3A30&tripName=H%C3%A0+N%E1%BB%99i+-+Th%C3%A1i+B%C3%ACnh++8h30";
        $ticketData = [];
        parse_str($formData, $ticketData);
        // return $ticketData;
        $ticketData['userId'] = 0;
        if (auth('web')->check()) {
            $ticketData['userId'] = auth('web')->user()->id;
        } else {
            $passenger = $this->userRepository->findByEmail($ticketData['passengerEmail']);

            $ticketData['userId'] = ($passenger != null) ?? $passenger->id;
        }

        $trip = $this->tripDepartDateRepository->find($ticketData['tddId']);
        if (!isset($trip) && $trip->is_active == 0) {
            return null;
        }

        $selectedSeats = explode(',', $ticketData['selectedSeats']);

        $seatMap = $trip->seatMap();
        foreach ($selectedSeats as $index) {
            if ($seatMap[$index] == 1) {
                return response()->json([
                    'status' => "error",
                    'data' => null,
                    "message" => __("Seat :index has been booked", ['index' => $index])
                ]);
            } else {
                $seatMap[$index] = 1;
            }
        }

        DB::beginTransaction();
        try {
            $ticket = $this->ticketRepository->createTicket($ticketData);
            $trip->available_seats = $trip->available_seats - $ticketData['quantity'];
            $trip->seat_map = json_encode($seatMap);
            $trip->save();
            $ticketData['viewName'] = 'web.booking.mail';
            $ticketData['subject'] = __('Booking ticket successfully!');
            $ticketData['ticketCode'] = $ticket->code;
            Mail::to($ticketData['passengerEmail'])->send(new SendMail($ticketData));
            $view = view('web.booking.step4', ['ticketData' => $ticketData])->render();
            // SendMailJob::dispatch($ticketData);
            
            $brand = $ticket->brand;
            $routeName = $ticketData['routeName'];
            $this->newTicketNotification($brand, $ticket, $routeName);
            DB::commit();
            return response()->json([
                    'status' => "success",
                    'data' => [
                        'view' => $view
                    ],
                    "message" => __('Book ticket successfully')
                ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $view = view('web.booking.step4', ['ticketData' => null])->render();
            return response()->json([
                    'status' => "error",
                    'data' => [
                        'view' => $view
                    ],
                    "message" => 'Add ticket fail'
                ]);
        }
        
    }

    public function newTicketNotification(Brand $brand,Ticket $ticket, $routeName)
    {
        $data = [
            'type' => 'ticket',
            'message' => "Tuyến xe $routeName có 1 vé xe mới ",
            'code' => $ticket->code,
            'time' => date('H:i:s d-m-Y', strtotime($ticket->created_at)),
        ];
        if ($brand) {
            
            $brand->notify(new NewTicketNotification($data));
        }

        $data1 = [
            'type' => 'ticket',
            'message' => "Tuyến xe $routeName có 1 vé xe mới ",
            'route' => route('admin.ticket.show', [
                'code' => $ticket->code
            ]),
            'time' => date('H:i:s d-m-Y', strtotime($ticket->created_at)),
            'brand_id' => $brand->id
        ];

        $options = [
            'cluster' => 'ap1',
            'useTLS' => true
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('my-channel', 'my-event', $data1);
        // event(new NewTicketEvent($data));
    }



    public function test()
    {
        $ticketCode = '1VlbMVge3jE';
        $this->ticketRepository->rollback($ticketCode);
    }

    public function tracking(Request $request)
    {
        $code = request('ticket_code');

        $ticket = $this->ticketRepository->findByCode($code) ?? null;

        $checkCanCancel = $ticket ? !$ticket->isExpiredInHours(config('constants.UNPAID_TICKET_HOUR_LIMIT')) : false;
        
        return view('web.tracking.index', [
            'ticket' => $ticket,
            'checkCanCancel' => $checkCanCancel
        ]);
    }

    public function sendCancelTicketMail(Request $request)
    {
        $ticket = $this->ticketRepository->findByCode($request->ticketCode);

        if (!$ticket) {
            return response()->json(createResponse(404, [], __('Do not find ticket with code:' . $request->ticketCode)));
        }
        $randomNumberString = str_random(6);
        $ticketData = [
            'to' => $ticket->passenger_info['email'],
            'cancelTicketCode' => $randomNumberString,
            'ticketCode' => $ticket->code,
            'passengerName' => $ticket->passenger_info['name'],
        ];
        
        $ticketData['view'] = 'web.mail.cancel_ticket';
        $ticketData['subject'] = __('Cancel ticket request!');
        Mail::to($ticketData['to'])->send(new CancelTicketMail($ticketData));

        $data = [
            'cancelTicketCode' => $randomNumberString,
            'timeExpire' => time() + 100
        ];
        Session::put("cancelTicketData$request->ticketCode", $data);
        return response()->json(createResponse(200, $data, __('Check your mail to get cancel ticket code')));
    }

    public function cancelTicket(Request $request)
    {
        $cancelCode = $request->cancelCode;
        $ticketCode = $request->ticketCode;
        
        if (!Session::has("cancelTicketData$ticketCode")) {
            return response()->json(createResponse(404, [], __('Your code is invalid'))); 
        }

        $cancelTicketData = Session::get("cancelTicketData$ticketCode");

        if ($cancelCode != $cancelTicketData['cancelTicketCode'] || $cancelTicketData['timeExpire'] < time()) {
            return response()->json(createResponse(505, [], __('Your code has expired')));
        }

        try {
            $ticket = $this->ticketRepository->findByCode($ticketCode);

            if ($ticket->isExpiredInHours(Ticket::$defaultExpiredHours)) {
                return response()->json(createResponse(400, [], __('Ticket cancellation period has expired')));
            }

            if ($ticket->code != TicketStatus::getValue('Cancel')) {
                DB::transaction(function () use ($ticket){
                    $this->ticketRepository->changeStatus($ticket->code, $ticket->getNextCancelStatus());
                    $this->ticketRepository->rollback($ticket->code);
                });
            }
        } catch (\Throwable $th) {
            return response()->json(createResponse(400, [], __('Cancel ticket fail, please try again')));
        }
        Session::forget("cancelTicketData$ticketCode");
        return response()->json(createResponse(200, [], __('Cancel ticket successfully'))); 
    }

}
