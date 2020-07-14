<?php

namespace App\Contracts\Repositories;

interface TicketRepository extends BaseRepository
{
    public function all();
    public function paginate($items = null);
    public function find($id);
    public function findByCode($id);
    public function createTicket($data);
    public function changeStatus($code, $status);
    public function allByAdmin();
    public function rollback($ticketCode);
    public function getTicketByDate($date);
    public function getTicketByMonth($month);
    public function searchByAdmin($data);
}
