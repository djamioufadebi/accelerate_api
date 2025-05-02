<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = ['invoice_id', 'description', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

}
