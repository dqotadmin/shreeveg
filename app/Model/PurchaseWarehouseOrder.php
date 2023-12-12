<?php

namespace App\Model;

use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PurchaseWarehouseOrder extends Model
{

    public function brokerDetail()
    {
        return $this->belongsTo(Admin::class, 'broker_id');
    }

    public function warehouseDetail()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function purchaseWarehouseOrderDetail()
    {
        return $this->hasMany(PurchaseWarehouseOrderDetail::class, 'purchase_warehouse_order_id');
    }
    public function receiverName()
    {
        return $this->belongsTo(Admin::class, 'created_by_id');
    }
}
