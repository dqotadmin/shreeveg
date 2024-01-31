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

    public function scopeWithinRadiusOld($query, $latitude, $longitude)
    {
        // Haversine formula to calculate distances
        $query->select('*')
            ->selectRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) *
                    cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) *
                    sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->whereRaw('distance < radius')
            ->orderBy('distance', 'asc');
    }

    public function scopeWithinRadius($query, $latitude, $longitude)
    {
        // Haversine formula to calculate distances

        $searchRadius = 100;

        // Haversine formula to calculate distance
        $distanceExpression = sprintf(
            '(6371 * acos(cos(radians(%s)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%s)) + sin(radians(%s)) * sin(radians(latitude))))',
            $latitude,
            $longitude,
            $latitude
        );

        // Query to find nearby warehouses
        $query
            ->select('id', 'name', 'latitude', 'longitude')
            ->selectRaw("{$distanceExpression} AS distance")
            ->whereRaw("{$distanceExpression} < ?", [$searchRadius])
            ->whereNull('deleted_at') // Assuming you have a soft delete column
            ->orderBy('distance')
            ->get();


        // $query->select('*')
        //     ->selectRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) *
        //             cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) *
        //             sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
        //     ->whereRaw('distance < radius')
        //     ->orderBy('distance', 'asc');
    }

    public function getWarehouseAdmin()
    {
        return $this->hasOne(Admin::class, 'warehouse_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
}
