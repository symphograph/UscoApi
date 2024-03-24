<?php

namespace App\Files\Transfer;

use App\Docs\Doc;
use Symphograph\Bicycle\Files\FileDoc;
use PDO;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\PDO\DB;

class ExtractOldDocs
{
    public static function execute()
    {
        $list = self::getList();

        foreach ($list as $doc) {
            $oldRelPath = 'documents/' . $doc->fileName;
            $oldFullPath = FileHelper::fullPath($oldRelPath);
            if(!FileHelper::fileExists($oldFullPath)){
                echo '-<br>';
                continue;
            }
            echo '+<br>';
            $md5 = md5_file($oldFullPath);
            $ext = pathinfo($doc->fileName,PATHINFO_EXTENSION);
            $file = FileDoc::newInstance($md5, $ext);
            $doc->file = $file;
            $newPath = $file->getFullPath();
            FileHelper::copy($oldFullPath, $newPath);
            $doc->putToDB();
            $doc->makePublic();
            printr($doc);
            //var_dump(FileHelper::fileExists($oldFullPath));
        }
    }

    /**
     * @return Doc[]
     */
    private static function getList(): array
    {
        $qwe = DB::qwe("select * from Docs");
        return $qwe->fetchAll(PDO::FETCH_CLASS, Doc::class);

        printr($list);
        die();
    }

    public static function getSinonim(string $fileName): array
    {
        $qwe = DB::qwe("select * from Docs");
        return $qwe->fetchAll(PDO::FETCH_CLASS, Doc::class);
    }
}