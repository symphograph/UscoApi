<?php

class Helpers
{
    public static function dateToFirstDayOfMonth(string $date)
    {
        if(!self::isDate($date)){
            return false;
        }

        return date('Y-m-01',$date);
    }

    public static function isDate(string $date, string|array $format = 'Y-m-d'): bool
    {
        if(!is_array($format)){
            return date($format, strtotime($date)) === $date;
        }
        foreach ($format as $f){
            if(date($f, strtotime($date)) === $date)
                return true;
        }
        return false;
    }

    public static function isMyClassExist(string $className) {
        $fileName = str_replace('\\', '/', $className) . '.php';
        if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/classes/' . $fileName)){
            return false;
        }
        return class_exists($className);
    }

    /**
     * Принимает массив объектов и меняет его ключи на значение указанного поля
     */
    public static function colAsKey(array $List, string $key): array|bool
    {
        $arr = [];
        foreach ($List as $Object){
            if(!isset($Object->$key))
                return false;
            $arr[$Object->$key] = $Object;
        }
        return $arr;
    }


}