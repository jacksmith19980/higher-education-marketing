<?php

namespace App\Widgets;

use Illuminate\Http\Request;

class Widgets
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
