<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'email',
        'phone_number',
        'hire_date',
        'specialization',
        'status',
        'department_id',
    ];

    // 🔗 Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'professor_id', 'id');
    }
}