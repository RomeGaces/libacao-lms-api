<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

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
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'course_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'course_id', 'id');
    }
}

