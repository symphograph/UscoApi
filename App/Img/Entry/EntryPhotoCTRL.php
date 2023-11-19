<?php

namespace App\Img\Entry;

use App\Img\FileImg;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

class EntryPhotoCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        if(empty($_FILES)){
            throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
        }
        $entryId = intval($_POST['entryId'] ?? 0) or throw new ValidationErr();

        foreach ($_FILES as $FILE){
            $file = new FileImg($FILE);
            $Photo = new EntryPhoto($entryId);
            $Photo->upload($file);

        }

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);
        $entryId = intval($_POST['entryId'] ?? 0) or throw new ValidationErr();
        $baseName = $_POST['fileName'] ?? throw new ValidationErr();

        $Photo = new EntryPhoto($entryId, $baseName);
        $Photo->delFiles();
        Response::success();
    }
}