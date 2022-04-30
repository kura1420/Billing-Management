<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaProductPromo extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];
}
