<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{

     protected $fillable=[
        'store_id',
        'product_id',
        'total_stock',
     ];
     public function product() 
     {
         return $this->hasOne(Product::class, 'id','product_id');
     }
     public function unit() 
     {
         return $this->belongsTo(Unit::class, 'unit_id');
     }
}
