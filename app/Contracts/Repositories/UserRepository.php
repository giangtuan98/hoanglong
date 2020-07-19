<?php

namespace App\Contracts\Repositories;

interface UserRepository extends BaseRepository
{
    public function all();
    public function paginate($items = null);
    public function find($id);
    public function findByEmail($id);
}
