<?php

namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $fillable = ['deleted_by'];
    protected $dates = ['deleted_at'];
    

    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function scopeWithinRadius($query, $latitude, $longitude)
    {
        // Haversine formula to calculate distances
        $query->select('*')
            ->selectRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) *
                    cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) *
                    sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->whereRaw('distance < radius')
            ->orderBy('distance', 'asc');
    }
   
 
}
