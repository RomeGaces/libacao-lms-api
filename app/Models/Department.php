<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_code',
        'department_name',
        'office_location',
        'contact_email',
        'contact_number',
    ];

    // Relationships
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id', 'id');
    }

    public function professors()
    {
        return $this->hasMany(Professor::class, 'department_id', 'id');
    }
    
}