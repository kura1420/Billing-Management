<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDevice extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
