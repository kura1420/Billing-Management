<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingInvoice extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function customer_data()
    {
        return $this->belongsTo(CustomerData::class, 'customer_data_id');
    }

    public function billing_types()
    {
        return $this->belongsTo(BillingType::class, 'billing_type_id');
    }

    public function product_types()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function product_services()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'verif_by_user_id');
    }
}
