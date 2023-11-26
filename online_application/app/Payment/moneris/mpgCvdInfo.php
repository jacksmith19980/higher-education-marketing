<?php

namespace App\Payment\moneris;

class mpgCvdInfo
{

    var $params;
    var $cvdTemplate = array('cvd_indicator','cvd_value');

    function __construct($params)
    {
        $this->params = $params;
    }

    function toXML()
    {
        $xmlString = "";

        foreach($this->cvdTemplate as $tag)
        {
            $xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
        }

        return "<cvd_info>$xmlString</cvd_info>";
    }

}//end class
