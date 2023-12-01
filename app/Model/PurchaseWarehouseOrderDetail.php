<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PurchaseWarehouseOrderDetail extends Model
{

    public function purchaseWarehouseOrderList()
    {
        return $this->belongsTo(PurchaseWarehouseOrder::class, 'purchase_warehouse_order_id');
    }


    public function productDetail()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
