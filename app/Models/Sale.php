<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Staff who made the sale
        'customer_id',
        'invoice_number',
        'total_amount',
        'discount',
        'final_total',
        'payment_type', // cash, card, qr
        'status', // completed, refunded
    ];

    // 🟢 RELATIONSHIP 1: Link to Staff (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🟢 RELATIONSHIP 2: Link to Customer (Optional)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🟢 RELATIONSHIP 3: Link to Sale Items
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}