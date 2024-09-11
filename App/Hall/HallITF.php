<?php

namespace App\Hall;

interface HallITF
{
    public static function byId(int $id);
    public static function byName(string $name);
    public static function bySuggestId(int $suggestId);

    public function getName(): string;

    public function getSuggestId();
}