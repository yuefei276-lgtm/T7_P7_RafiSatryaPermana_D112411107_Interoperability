<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'category',
        'price',
        'stock',
        'brand',
        'description',
        'status',
    ];

    public $timestamps = true;
}
