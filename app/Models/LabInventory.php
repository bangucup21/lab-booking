<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabInventory extends Model
{
    protected $table = 'lab_inventories';

    protected $fillable = [
        'resource_id', 'name', 'quantity', 'condition', 'notes',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
