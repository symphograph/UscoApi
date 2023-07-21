<?php
namespace App;

use PDO;

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

    /**
     * @return array<self>
     */
    public static function getList(): array
    {
        $qwe = qwe("SELECT id as id, name, map FROM halls");
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,get_class());
    }


}