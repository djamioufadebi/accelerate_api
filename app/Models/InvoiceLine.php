<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceLine extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'description', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

}
