<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BrokerRateListDetail extends Model
{
    protected $casts = [
        'broker_rate_list_id' => 'integer',
    ];



    public function rateList()
    {
        return $this->belongsTo(BrokerRateList::class, 'broker_rate_list_id');
    }
}
