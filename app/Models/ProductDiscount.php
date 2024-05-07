<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'discount'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected $cast = [
        'discount' => 'float'
    ];
}
