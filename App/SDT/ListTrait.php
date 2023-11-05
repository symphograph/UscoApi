<?php

namespace App\SDT;

trait ListTrait
{
    public function getListAsArrays(): array
    {
        $arrays = [];
        foreach ($this->list as $object){
            $arrays[] = $object->getAllProps();
        }
        return $arrays;
    }

    public function putListToDB(): void
    {
        $rows = self::getListAsArrays();
        $chunkSize = 1000;
        $totalRows = count($rows);

        for ($i = 0; $i < $totalRows; $i += $chunkSize) {
            $chunk = array_slice($rows, $i, $chunkSize);
            DB::insertRows(self::tableName, $chunk);
        }
    }
}