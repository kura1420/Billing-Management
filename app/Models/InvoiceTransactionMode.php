<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTransactionMode extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function billing_invoices()
    {
        return $this->belongsTo(BillingInvoice::class, 'billing_invoice_id');
    }
}
