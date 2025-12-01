<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrar extends Model
{
    use HasFactory;

    protected $primaryKey = 'registrar_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'employee_number',
        'department_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}