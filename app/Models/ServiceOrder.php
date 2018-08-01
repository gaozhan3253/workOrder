<?php

namespace App\Models;


class ServiceOrder extends Base
{
   protected $guarded  = [];

   public function serviceOrderDetails()
   {
      return $this->hasMany('App\Models\ServiceOrderDetail','order_id','order_id');
   }

   public function serviceOrderBuyer() 
   {
      return $this->belongsTo('App\Models\ServiceOrderBuyer','order_id','order_id');
   }
}
