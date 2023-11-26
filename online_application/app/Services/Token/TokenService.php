<?php

namespace App\Services\Token;

class TokenService
{
    public function replace($map, $content)
    {
        foreach ($map as $key => $value) {
            $content = str_replace('%'.$key.'%', $value, $content);
        }

        return $content;
    }
}
