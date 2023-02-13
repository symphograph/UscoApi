<?php
namespace App;

class Validator
{
    public static function dateTime(string $datetime): bool|string
    {
        if(date('Y-m-d H:i:s', strtotime($datetime)) == $datetime){
            return $datetime;
        }

        if(date('Y-m-d H:i', strtotime($datetime)) == $datetime){
            return $datetime . ':00';
        }
        return false;
    }

    public static function date(string $date) : bool|string
    {
        if(date('Y-m-d', strtotime($date)) == $date){
            return $date;
        }
        return false;
    }

}