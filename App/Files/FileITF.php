<?php

namespace App\Files;

interface FileITF
{
    function validate(): void;

    function getFullPath(): string;
}