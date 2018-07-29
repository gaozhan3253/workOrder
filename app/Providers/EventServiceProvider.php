<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //创建订单事件
        'App\Events\CreateWorkOrderEvent' => [
            'App\Listeners\CreateWorkOrderEventListener',
        ],
        //优选渠道事件
        'App\Events\OptimizationChannelEvent'=>[
          'App\Listeners\OptimizationChannelEventListener'
        ],
        //推送渠道物流订单事件
        'App\events\PushChannelOrderEvent'=>[
            'App\Listeners\PushChannelOrderEventListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
