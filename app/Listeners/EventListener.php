<?php

namespace App\Listeners;

use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventListener implements ShouldQueue
{

    public $connection = 'database';

    public $queue = 'default';

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
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        try{
            $user = $event->getUser();

            $user->id += 1;

            $user->save();

        }catch (\Error $e){
            dump($e);
        }
    }

    public function failed(Event $event, $exception)
    {
        dump('Failed', $event, $exception);
    }
}
