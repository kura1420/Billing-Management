<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromo extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function product_types()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function product_promo_services()
    {
        return $this->hasOne(ProductPromoService::class);
    }
}
