<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name', 'pin', 'organization_id', 'grade_level', 'major',
        'student_count', 'academic_year', 'semester', 'metadata', 'is_active',
    ];

    protected $casts = [
        'metadata'  => 'array',
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
