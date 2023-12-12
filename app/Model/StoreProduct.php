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
     
}
