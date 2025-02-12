<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function partners()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
