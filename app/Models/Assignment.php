<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';

    protected $fillable = [
        'teacher_id', 'class_name', 'title', 'subject_name',
        'description', 'deadline', 'is_active', 'allow_student_download',
        'attachment_path', 'series_id', 'session_number',
    ];

    protected $casts = [
        'deadline'               => 'datetime',
        'is_active'              => 'boolean',
        'allow_student_download' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
