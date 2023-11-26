<?php

namespace App\Http\ViewComposers;

use App\School;
use Illuminate\View\View;

class StudentViewComposer
{
    public function compose(View $view)
    {
        if ($student = auth()->guard('student')->user()) {
            $view->with('student', $student);
        }
    }
}
