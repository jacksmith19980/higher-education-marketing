<?php

namespace App\Http\ViewComposers;

use App\Repositories\Interfaces\SchoolRepositoryInterface;
use App\School;
use Illuminate\View\View;

class SchoolAuthViewComposer
{
    public function compose(View $view)
    {
        //
        if (session('school')) {
            $view->with('school', session('school'));
            return;
        }
    }
}
