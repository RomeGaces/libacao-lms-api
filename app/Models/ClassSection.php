<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'section_name',
        'course_id',
        'year_level',
        'school_year_id',
        'semester_id',
        'capacity',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function studentSubjectAssignments()
    {
        return $this->hasMany(StudentSubjectAssignment::class, 'class_section_id', 'id');
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_section_id', 'id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
}
