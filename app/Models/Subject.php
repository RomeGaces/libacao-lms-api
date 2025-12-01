<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $primaryKey = 'subject_id';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'units',
        'semester',
        'year_level',
        'course_id',
    ];

    // 🔗 Relationships

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'subject_id', 'subject_id');
    }

    public function studentAssignments()
    {
        return $this->hasMany(StudentSubjectAssignment::class, 'subject_id', 'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

