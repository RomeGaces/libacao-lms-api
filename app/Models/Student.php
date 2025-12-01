<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birth_date',
        'course_id',
        'email',
        'phone_number',
        'address',
    ];

    // 🔗 Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subjectAssignments()
    {
        return $this->hasMany(StudentSubjectAssignment::class, 'student_id');
    }

    
}