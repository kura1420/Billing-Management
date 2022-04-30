<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CustomerProfile extends Model
{
    use HasFactory, Uuid, Notifiable;

    public $incrementing = false;

    protected $guarded = [];

    public function customer_data()
    {
        return $this->belongsTo(CustomerData::class, 'customer_data_id');
    }
}
