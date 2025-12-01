<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year_start',
        'year_end',
        'is_active',
    ];

    protected $appends = ['name'];

    // Auto-computed field (ex: "2024–2025")
    public function getNameAttribute()
    {
        return $this->year_start . ' - ' . $this->year_end;
    }

    public function classSections()
    {
        return $this->hasMany(ClassSection::class, 'school_year_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_year_id');
    }
}
