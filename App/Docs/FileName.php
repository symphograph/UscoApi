<?php

namespace App\Docs;

class FileName
{
    const string prefix = 'USSO';

    public int $id;
    public string $date;

    public function __construct(public string $value) {

    }

    public static function extractProps() {

    }

    public function extractId(): false|int
    {
        $arr = explode('~id', $this->value);
        if(empty($arr)) {
            return false;
        }
        $id = pathinfo(end($arr), PATHINFO_FILENAME);
        $id = intval($id);

        return $id ?: false;
    }

}