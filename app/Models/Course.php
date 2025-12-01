<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'duration_years',
        'department_id',
    ];

    // 🔗 Relationships

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'course_id', 'course_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'course_id', 'course_id');
    }
}

