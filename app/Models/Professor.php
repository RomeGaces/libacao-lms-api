<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public static function boot()
    {
        parent::boot();

        static::created(function ($professor) {

            // Create user only if not existing
            User::firstOrCreate(
                ['professor_id' => $professor->id],
                [
                    'name' => $professor->first_name . ' ' . $professor->last_name,
                    'email' => $professor->email
                        ?? strtolower(Str::slug($professor->first_name . $professor->last_name))
                        . rand(100, 999) . '@libacao-university.edu',
                    'password' => 'password123',
                    'is_admin' => true,
                ]
            );
        });
    }
}
