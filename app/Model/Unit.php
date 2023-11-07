<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }
     protected $casts = [
 
        'status' => 'integer'
    ];

  
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    protected static function booted(): void
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function($query){
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
