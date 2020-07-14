<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\TicketRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    protected $ticketRepository;

    public function __construct(TicketRepository $ticketRepository) {
        $this->ticketRepository = $ticketRepository;
    }

    public function index()
    {
        $admin = getAuthAdmin();
        
        if ($admin->hasAccess(config('permission.view_ticket.slug'))) {
            $todayTicket = [];
            $monthTicket = [];
        } else {
            $today = date('Y-m-d');
            $todayTicket = $this->ticketRepository->getTicketByDate($today);
            
            $month = date('m');
            $monthTicket = $this->ticketRepository->getTicketByMonth($month);
        }

        return view('admin.home.index', [
            'todayTicket' => $todayTicket,
            'monthTicket' => $monthTicket,
        ]);
    }
}
