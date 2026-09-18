<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = [
        'name', 'type', 'parent_id', 'address', 'phone', 'email', 'metadata', 'is_active',
    ];

    protected $casts = [
        'metadata'  => 'array',
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classes()
    {
        return $this->hasMany(LabClass::class);
    }
}
