<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    public static $defaultExpiredHours = 6;

    protected $table = "tickets";

    protected $fillable = [
        'trip_depart_date_id',
        'quantity',
        'passenger_info',
        'brand_id',
        'list_seat',
        'total',
        'user_id',
        'unit_price',
        'code',
        'trip_info',
        'status',
    ];

    protected $colorList = [
        TicketStatus::Unpaid => 'info',
        TicketStatus::Paid => 'success',
        TicketStatus::Cancel => 'dark',
        TicketStatus::Refund => 'warning',
        TicketStatus::NotRefundYet => 'danger',
    ];

    public function tripDepartDate()
    {
        return $this->belongsTo(TripDepartDate::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPassengerInfoAttribute($value)
    {
        $passengerInfo = json_decode($value, true);
        return $passengerInfo;
    }

    public function getTripInfoAttribute($value)
    {
        $tripInfo = json_decode($value, true);
        return $tripInfo;
    }

    public function getStatusAttribute($value)
    {
        return __($value);
    }

    public function getColor($status)
    {
        return $this->colorList[$status];
    }

    public function getStatusColor()
    {
        return $this->getColor($this->getOriginal('status'));
    }

    public function scopeBrand(Builder $builder) {
        $brandId = getAuthAdminBrandId();
        
        $builder->where('brand_id', '=', $brandId);
    }

    public function scopeIsBooked(Builder $builder)
    {
        $builder->where('status', '=', TicketStatus::getValue('Paid'))->orWhere('status', '=', TicketStatus::getValue('Unpaid'));
    }

    public function getListSeatString()
    {
        $list = json_decode($this->list_seat, true);
        return join(", ", $list);
    }

    public function getNextStatus()
    {
        switch ($this->getOriginal('status')) {
            case TicketStatus::Unpaid:
                return TicketStatus::Paid;
            case TicketStatus::Paid:
                return TicketStatus::NotRefundYet;
            case TicketStatus::NotRefundYet:
                return TicketStatus::Refund;
            default:
                return null;
        }
    }

    public function getNextCancelStatus()
    {
        switch ($this->getOriginal('status')) {
            case TicketStatus::Unpaid:
                return TicketStatus::Cancel;
            case TicketStatus::Paid:
                return TicketStatus::NotRefundYet;
            default:
                return null;
        }
    }

    public function getNextStatusColor()
    {
        $nextStatus = $this->getNextStatus();
        return $nextStatus ? $this->getColor($nextStatus) : null;
    }

    public function getNextStatusAttribute()
    {
        $nextStatus = $this->getNextStatus();
        return __($nextStatus);
    }

    public function isExpiredInHours($hours)
    {
        // $hours = isset($hours) ? $hours : $this->hoursExpired;
        $runDate = $this->tripDepartDate->depart_date;
        $runTime = $this->trip_info['depart_time'];
        $runTimestamp = $runDate . " " . $runTime;

        return strtotime($runTimestamp) < (time() + $hours * 3600);
    }
}
