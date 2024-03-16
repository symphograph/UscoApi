<?php

namespace App\Files\Transfer;

use App\Docs\Doc;
use App\Files\FileDoc;
use PDO;
use Symphograph\Bicycle\FileHelper;

class ExtractOldDocs
{
    public static function execute()
    {
        $list = self::getList();

        foreach ($list as $doc) {
            $oldRelPath = 'documents/' . $doc->fileName;
            $oldFullPath = FileHelper::fullPath($oldRelPath);
            if(!FileHelper::fileExists($oldFullPath)) continue;
            $md5 = md5_file($oldFullPath);
            $ext = pathinfo($doc->fileName,PATHINFO_EXTENSION);
            $file = FileDoc::newInstance($md5, $ext);
            $doc->file = $file;
            $newPath = $file->getFullPath();
            FileHelper::copy($oldFullPath, $newPath);
            $doc->putToDB();
            //var_dump(FileHelper::fileExists($oldFullPath));
        }
    }

    /**
     * @return Doc[]
     */
    private static function getList(): array
    {
        $qwe = qwe("select * from Docs");
        return $qwe->fetchAll(PDO::FETCH_CLASS, Doc::class);
    }
}