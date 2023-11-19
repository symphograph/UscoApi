<?php

namespace App\Entry\Sections;

interface SectionITF
{
    function getType(): string;

    function getContent(): string;
}