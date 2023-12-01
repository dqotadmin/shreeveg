<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{

    public function warehouseDetail()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


    public function productDetail()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
