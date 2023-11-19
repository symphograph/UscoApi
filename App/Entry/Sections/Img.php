<?php

namespace App\Entry\Sections;

class Img extends Section
{
    public readonly string $type;

    public function __construct(protected string $input = '')
    {
        parent::__construct($input);
        $this->type = 'img';
        $this->extractValue();
    }
}