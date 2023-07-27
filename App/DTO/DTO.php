<?php

namespace App\DTO;

class DTO
{
    protected function bindSelf(object $Object): void
    {
        $vars = get_class_vars($this::class);
        foreach ($vars as $k => $v) {
            if (!isset($Object->$k)) continue;
            $this->$k = $Object->$k;
        }
    }
}