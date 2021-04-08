<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    public function firstVariant()
    {
        return $this->belongsTo('App\Models\ProductVariant', 'product_variant_one');
    }
    public function secondVariant()
    {
        return $this->belongsTo('App\Models\ProductVariant', 'product_variant_two');
    }
    public function thirdVariant()
    {
        return $this->belongsTo('App\Models\ProductVariant', 'product_variant_three');
    }
}
