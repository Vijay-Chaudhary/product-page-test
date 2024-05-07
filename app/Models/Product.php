<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProductImage;
use App\Models\ProductDiscount;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'active'
    ];

    public function images() {
        return $this->hasMany(ProductImage::class);
    }
    public function discount() {
        return $this->hasOne(ProductDiscount::class);
    }
}
