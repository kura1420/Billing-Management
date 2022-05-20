<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromoService extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function product_promos()
    {
        return $this->belongsTo(ProductType::class, 'product_promo_id');
    }

    public function product_services()
    {
        return $this->belongsToMany(ProductPromo::class, 'product_service_id');
    }
}
