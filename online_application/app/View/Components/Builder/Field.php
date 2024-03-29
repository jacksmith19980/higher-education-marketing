<?php

namespace App\View\Components\builder;

use Illuminate\View\Component;

class Field extends Component
{
    public $field;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field = null)
    {
        $this->field = $field;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.builder.field');
    }
}
