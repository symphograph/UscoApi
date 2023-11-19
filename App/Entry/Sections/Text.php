<?php

namespace App\Entry\Sections;

class Text extends Section
{
    public readonly string $type;

    public function __construct(protected string $input = '')
    {
        parent::__construct($input);
        $this->type = 'text';
        $input = str_replace(PHP_EOL, '<br>', $input);
        $this->content = $input.'<br>';
    }
}