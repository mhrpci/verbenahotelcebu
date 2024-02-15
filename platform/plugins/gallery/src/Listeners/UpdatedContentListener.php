<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Exception;
use Gallery;

class UpdatedContentListener
{

    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
    {
        try {
            Gallery::saveGallery($event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
