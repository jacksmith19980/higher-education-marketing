<?php

namespace App\View\Components\Builder;

use Illuminate\View\Component;

class CustomField extends Component
{
    public $title;
    public $object;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null, $object = 'contacts')
    {
        $this->title = $title;
        $this->object = $object;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.builder.custom-field');
    }
}
