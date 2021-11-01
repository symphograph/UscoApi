<?php

class Hall
{
    public function __construct(
        public int $id = 0,
        public string $name = '',
        public string $map = ''
    )
    {
    }
}