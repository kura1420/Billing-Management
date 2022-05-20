<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function customer_profiles()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function product_services()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }

    public function area_products()
    {
        return $this->belongsTo(AreaProduct::class, 'area_product_id');
    }

    public function customer_promos()
    {
        return $this->hasMany(CustomerPromo::class);
    }
}
