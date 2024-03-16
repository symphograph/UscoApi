<?php

namespace App\Img\Entry;

use App\Files\FileIMG;
use App\Files\UploadedImg;
use App\Img\Photo;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\PDO\DB;

class EntryPhoto extends Photo
{
    protected array $sizes = [260, 1080];
    protected string $parentFolder = '/img/entry/photo';
    protected string $baseName;



    public function upload(UploadedImg $file): void
    {
        $md5 = $file->getMd5();
        $ext = $file->getExtension();

        $this->baseName = $md5 . '.' . $ext;
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $fileIMG = FileIMG::byUploaded($file);
        $file->saveAs($originFullPath);


        FileHelper::copy($originFullPath, $fileIMG->getFullPath());

        $fileIMG->putToDB();
        //self::linkToEntry($this->id, $fileIMG->idByPut());


        $this->makeSizes($originFullPath);
    }
}