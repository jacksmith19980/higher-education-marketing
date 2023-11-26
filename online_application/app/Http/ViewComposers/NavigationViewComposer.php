<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class NavigationViewComposer
{
    public function compose(View $view)
    {
        if (auth()->user()) {
            $view->with('schools', auth()->user()->schools);
        }
    }
}
