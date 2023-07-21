<?php
namespace App;

class TestRel
{
    public static function itWorks()
    {
        return match (false) {
            isPositive($num) => 'Значение отрицательное',
            !isFignya($num) => 'Значение фигня',
            !isOtherFignya($num) => 'Значение другая фигня',
            default => '$num - положительное число'
        };
        echo 'I am works from hear';
    }
}