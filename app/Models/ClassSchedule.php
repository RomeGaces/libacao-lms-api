<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'class_section_id',
        'professor_id',
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }
}
