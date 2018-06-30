<?php

namespace App\Listeners;

use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('App\Events\Event', 'App\Listeners\EventListener');
    }
}
