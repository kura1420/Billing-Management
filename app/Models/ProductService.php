<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function product_types()
    {
        return $this->belongsTo(ProductType::class);
    }
}
