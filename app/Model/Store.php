<?php

namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = ['deleted_by'];
    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

  public function city(){
    return $this->belongsTo(City::class,'city_id');

  }
  
  public function warehouse(){
    return $this->belongsTo(Warehouse::class,'warehouse_id');

  }
  public function area(){
    return $this->belongsTo(CityArea::class,'area_id');

  }
 
}
