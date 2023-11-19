<?php

namespace App\Entry\Sections;

abstract class Section
{
    public readonly string $type;
    public string          $content;
    protected string       $pattern = '/!\[\w*\]\((.*?)\)/';

    public function __construct(protected string $input = '')
    {

    }

    protected function extractValue(): void
    {
        preg_match($this->pattern, $this->input, $matches);
        $this->content = $matches[1] ?? null;
    }
}