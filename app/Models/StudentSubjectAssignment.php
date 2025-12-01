<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectAssignment extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'student_id',
        'subject_id',
        'class_section_id',
        'status',
        'grade',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id', 'id');
    }
}
