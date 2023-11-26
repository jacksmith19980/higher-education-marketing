<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;
use App\Tenant\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddSubmittionNotification
{
    /**
     * Handle the event.
     *
     * @param  ApplicationSubmissionEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {
        $notification = new Notification();
        $notification->status = 'new';
        $notification->text = '<a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                        <span class="mail-contnet">
                                            <h5 class="message-title">'.$event->student->name.'</h5> <span class="mail-desc">Submitted new Application!</span> <span class="time">'.$event->submission->created_at.'</span> </span>
                                    </a>';
        $notification->route = 'notifications.show';
        $notification->model = 'student';
        $notification->model_id = $event->student->id;
        $notification->save();
    }
}
