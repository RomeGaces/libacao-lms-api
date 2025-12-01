<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'draft_id';

    protected $fillable = [
        'subject_id',
        'professor_id',
        'room_id',
        'class_section_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status',
        'notes',
        'generated_by',
    ];

    // Relationships
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

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id', 'id');
    }
}
