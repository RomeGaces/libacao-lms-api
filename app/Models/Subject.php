<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'units',
        'semester',
        'year_level',
        'course_id',
        'type',
        'hours_per_week',
        'description',
        'subject_prerequisite_id',
    ];

    // 🔗 Relationships

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'subject_id', 'id');
    }

    public function studentAssignments()
    {
        return $this->hasMany(StudentSubjectAssignment::class, 'subject_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
