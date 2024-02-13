<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'user_warehouse_orders';
    protected $casts = [
        'order_amount'           => 'float',
        'checked'                => 'integer',
        'warehouse_id'              => 'integer',
        'time_slot_id'           => 'integer',
        'coupon_discount_amount' => 'float',
        'total_tax_amount'       => 'float',
        'delivery_address_id'    => 'integer',
        'delivery_man_id'        => 'integer',
        'delivery_charge'        => 'float',
        'user_id'                => 'integer',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
        'delivery_address'       => 'array',
        'delivery_date'          => 'date',
        'free_delivery_amount'   => 'float',
        // 'cancel_by'              => 'string',
        // 'cancel_by_id'              => 'integer',
        
    ];
    protected $fillable=[
        'cancel_by','cancel_by_id'
    ];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class,'user_warehouse_order_id');
    }

    public function delivery_man(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }
    public function time_slot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function delivery_address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function scopePos($query)
    {
        return $query->where('order_type', '=' , 'pos');
    }

    public function scopeNotPos($query)
    {
        return $query->where('order_type', '!=' , 'pos');
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function storePerson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'store_sales_person_id');
    }
    public function product() 
    {
        return $this->hasOne(Product::class, 'product_id');
    }
   

}
