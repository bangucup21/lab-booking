<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slots';

    protected $fillable = [
        'name', 'start_time', 'end_time',
        'day_of_week', 'slot_order', 'is_break', 'is_after_hours', 'is_active',
    ];

    protected $casts = [
        'is_break'       => 'boolean',
        'is_after_hours' => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}