<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderBuyer extends Base
{
    // protected $guarded  = [];

    public function serviceOrder()
    {
        return $this->hasOne('App\Models\ServiceOrder','order_id','order_id');
    }
}
