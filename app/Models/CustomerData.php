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

    public function product_types()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
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

    public function customer_types()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    public function customer_segments()
    {
        return $this->belongsTo(CustomerSegment::class, 'customer_segment_id');
    }

    public function areas()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function provinsis()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
