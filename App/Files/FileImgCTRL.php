<?php

namespace App\Files;

abstract class FileImgCTRL
{
    protected static function addIMG(UploadedImg $tmpFile): FileIMG
    {
        $FileIMG = FileIMG::byUploaded($tmpFile);
        $tmpFile->saveAs($FileIMG->getFullPath());
        $FileIMG->putToDB();
        return $FileIMG;
    }
}