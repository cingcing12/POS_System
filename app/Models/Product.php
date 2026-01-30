<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'category_id', // <--- Added
        'barcode',     // <--- Added
        'cost_price',  // <--- Added
        'sale_price', 
        'qty', 
        'image_url'
    ];

    // Optional: Relationship to category
    public function category() {
        return $this->belongsTo(Category::class);
    }
}