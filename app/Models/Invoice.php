<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\InvoiceLine;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['client_id', 'user_id', 'number', 'date', 'total_ht'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    // Recalcul total HT
    public function calculateTotal()
    {
        $this->total_ht = $this->lines()->sum('amount');
        $this->save();
    }
    
}
