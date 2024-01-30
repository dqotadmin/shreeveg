<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CityArea extends Model
{
    protected $casts = [
 
        'status' => 'integer'
    ];

    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function cities()
    {
        return $this->hasOne(State::class);
    }
    public function cityDetail(){
        return $this->belongsTo(City::class, 'city_id');

    }
 
}
