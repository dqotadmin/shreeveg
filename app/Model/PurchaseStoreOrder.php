<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PurchaseStoreOrder extends Model
{

    public function storeDetail()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function warehouseDetail()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function purchaseStoreOrderDetail()
    {
        return $this->hasMany(PurchaseStoreOrderDetail::class, 'purchase_store_order_id');
    }
    // public function totalStoreStock()
    // {
    //     return $this->hasOne(StoreProduct::class, 'purchase_store_order_id');
    // }
    
}
