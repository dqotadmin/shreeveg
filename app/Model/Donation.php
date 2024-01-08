<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Donation extends Model
{


  public function warehouseDetail()
  {
    return $this->belongsTo(Warehouse::class, 'warehouse_id');
  }
  public function Store()
  {
    return $this->belongsTo(Store::class, 'store_id');
  }

  public function productDetail()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
}
