<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function provinsis()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}
