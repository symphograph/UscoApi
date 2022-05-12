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

    //public function __set(string $name, $value): void{}

    public static function getCollection(): array
    {
        $qwe = qwe("SELECT hall_id as id, hall_name name, map FROM halls");
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Hall");
    }


}