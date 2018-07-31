<?php

namespace App\Models;


class ServiceOrder extends Base
{
   protected $guarded  = [];

   public function serviceDetails()
   {
      return $this->hasMany('App\Models\ServiceDetail','order_id','order_id');
   }
}
