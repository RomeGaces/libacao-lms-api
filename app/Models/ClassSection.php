<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    use HasFactory;
    public $timestamps = true;  
    protected $primaryKey = 'class_section_id';

    protected $fillable = [
        'section_name', 
        'academic_year', 
        'semester',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function studentSubjectAssignments()
    {
        return $this->hasMany(StudentSubjectAssignment::class, 'class_section_id', 'class_section_id');
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_section_id', 'class_section_id');
    }
}