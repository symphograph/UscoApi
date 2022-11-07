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

    /**
     * @throws ReflectionException
     */
    public static function cloneFromAny(array|object $Inductor, object $Recipient): object
    {
        $className = get_class($Recipient);
        //var_dump($Inductor);
        $classVars = (object)get_class_vars($className);

        foreach ($Inductor as $k => $v) {

            //Убираем поля, которых класс не ожидает
            if (!property_exists($classVars, $k))
                continue;
            if (is_object($v) || is_array($v)) {

                //Если предлагаемое значение итерабельное, то решаем что с ним делать
                //$typeInClass = (new ReflectionProperty($className, $k))->getType()->getName();
                $ttt =  (new ReflectionProperty($className, $k))->getType();
                if($ttt::class === 'ReflectionNamedType'){
                    $typeInClass = $ttt->getName();
                }elseif ($ttt::class === 'ReflectionUnionType') {
                    $types = $ttt->getTypes();
                    $arr = [];
                    foreach ($types as $type){
                        $countVars = count(get_class_vars($type->getName()));
                        $arr[$countVars] = $type->getName();
                       // var_dump($countVars . '|' . $type->getName());
                    }
                    ksort($arr);
                    $typeInClass = array_pop($arr);
                }
                if ($typeInClass === 'array') {
                    //Массив просто инициализируем
                    $Recipient->$k = $v;
                    continue;
                }

                //Если это объект ожидаемого класса, инициализируем рекурсивно
                $Recipient->$k = self::cloneFromAny($v, new $typeInClass());
                continue;
            }

            //В простом случае инициализируем.
            // Если тип не приводится, выполнение прекратится с Fatal Error.
            $Recipient->$k = $v;
        }
        return $Recipient;
    }


}