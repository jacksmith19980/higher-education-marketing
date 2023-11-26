<?php
namespace App\Filters;

abstract class FiltersAbstrcat
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
