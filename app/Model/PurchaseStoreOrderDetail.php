<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PurchaseStoreOrderDetail extends Model
{
    protected $fillable=[
        'status'
    ];
    public function purchaseStoreOrderList()
    {
        return $this->belongsTo(PurchaseStoreOrder::class, 'purchase_store_order_id ');
    }


    public function productDetail()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
