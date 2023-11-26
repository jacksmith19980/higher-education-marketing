<?php

namespace App\Http\ViewComposers;

use App\Tenant\Models\Notification;
use Illuminate\View\View;

class NotificationsViewComposer
{
    public function compose(View $view)
    {
        if ($notifications = Notification::orderBy('id', 'desc')->get()) {
            $view->with('notifications', $notifications);
        }
    }
}
