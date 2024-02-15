<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Exception;
use Gallery;

class CreatedContentListener
{

    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            Gallery::saveGallery($event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
