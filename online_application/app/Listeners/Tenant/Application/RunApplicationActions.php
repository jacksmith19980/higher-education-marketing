<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;

class RunApplicationActions
{
    /**
     * Handle the event.
     *
     * @param  ApplicationSubmissionEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {
        $actions = $event->application->actions()->get();
        foreach ($actions as $action) {
            $actionClass = 'App\\Actions\\'.str_replace('-', '', $action->action);
            dispatch(new $actionClass(
                $action,
                $event->application,
                $event->submission,
                $event->student,
                $event->setting,
                $event->school
            ));
        }
    }
}
