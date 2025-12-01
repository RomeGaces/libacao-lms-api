<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $primaryKey = 'class_schedule_id';

    protected $fillable = [
        'subject_id',
        'class_section_id',
        'professor_id',
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
        'semester',
        'academic_year',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }
}
