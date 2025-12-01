<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_number',
        'building_name',
        'capacity',
        'type',
    ];

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'room_id');
    }
}