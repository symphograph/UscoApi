<?php

class APIusco
{
    public static function errorMsg(string $msg='Неизвестная ошибка') : string|bool
    {
        return json_encode(['error'=>$msg],JSON_UNESCAPED_UNICODE);
    }

    public static function resultMsg(string|array $msg = 'Готово'): bool|string
    {
        return json_encode(['result'=>$msg],JSON_UNESCAPED_UNICODE);
    }

    public static function resultData(array|object $data, string|array $msg = 'Готово')
    {
        return json_encode(['result'=>$msg,'data' => $data],JSON_UNESCAPED_UNICODE);
    }
}