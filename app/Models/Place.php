<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = "places";

    protected $fillable = [
        'name',
        'description',
        'address',
        'map_url',
        'district_id',
        'brand_id'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function departRoutes()
    {
        return $this->hasMany(Route::class, 'depart_place_id');
    }

    public function desRoutes()
    {
        return $this->hasMany(Route::class, 'des_place_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'passing_places', 'place_id', 'route_id');
    }
}
