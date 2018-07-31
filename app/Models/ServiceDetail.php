<?php

namespace App\Models;

class ServiceDetail extends Base
{
    //
    protected $guarded  = [];

    public function serviceOrder()
    {
        return $this->belongsTo('App\Models\ServiceOrder','order_id','order_id');
    }
}
