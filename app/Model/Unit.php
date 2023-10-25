<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
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

 
}
