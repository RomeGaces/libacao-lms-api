<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    // NOTE: Only keep this if your migration uses ->id('student_id')
    // protected $primaryKey = 'student_id';

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
        'school_year_id',
        'enrollment_date',
        'year_level',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
}
