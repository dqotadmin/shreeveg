<?php

namespace App\Model;

use App\User;
use App\VisitedProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseCategory extends Model
{
    protected $table = 'warehouse_categories';

    public function getCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
    ?>