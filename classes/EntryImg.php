<?php

class EntryImg extends Img
{
    public string|bool $error   = '';
    public string      $src1080 = '';
    public string      $srcOrig = '';

    public static function upload(array $FILE): EntryImg
    {
        $EntryImg = new EntryImg();

        $er = Img::uplErors($FILE);
        if($er){
            $EntryImg->error = $er;
            return $EntryImg;
        }

        if(!$EntryImg->createNameAndLinks(intval($_POST['id'] ?? 0), $FILE['tmp_name'],$FILE['name'])){
            $EntryImg->error = 'Ошибка при переименовании файла';
            return $EntryImg;
        }

        if(!$EntryImg->putToOriginals($FILE['tmp_name'])){
            $EntryImg->error = 'Ошибка при сохранении оригинала';
            return $EntryImg;
        }

        $EntryImg->initFileInfo($EntryImg->file);
        if(!$EntryImg->exist){
            $EntryImg->error = 'Ошибка при сохранении оригинала';
            return $EntryImg;
        }

        $width = ($EntryImg->width > 1080) ? 1080 : 0;

        if(!self::optimize($EntryImg->file,$EntryImg->src1080,$width)){
            $EntryImg->error = 'Ошибка при сжатии';
            return $EntryImg;
        }

        return new EntryImg($EntryImg->src1080);
    }

    private function createNameAndLinks(int $id,string $file, string $filename) : bool
    {
        if(!$id){
            return false;
        }
        if(str_starts_with($file,'/tmp') || str_starts_with($file,'/home')){
            $this->fileName = md5_file($file);
        }else{
            $this->fileName = md5_file($_SERVER['DOCUMENT_ROOT'] . '/' . $file);
        }

        $this->ext = pathinfo($filename,PATHINFO_EXTENSION);
        $this->file = '/img/entry/origins/' . $id . '/' . $this->fileName . '.' . $this->ext;
        $this->src1080 = '/img/entry/1080/' . $id . '/' . $this->fileName . '.jpg';
        return true;
    }

    private function putToOriginals(string $from): bool
    {
        return FileHelper::moveUploaded($from,$_SERVER['DOCUMENT_ROOT'] . $this->file);
    }

    public static function idByDir($file)
    {
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        $dir = explode('/', $dir);
        return intval(array_pop($dir));
    }

    public static function saveFromOld(string $file, int $id = 0)
    {
        $file = str_replace('%20',' ',$file);
        if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $file)){
            echo $file;
            return false;
        }
        $EntryImg = new EntryImg();

        $filename = pathinfo($file, PATHINFO_BASENAME);
        if(!$id)
            $id = self::idByDir($file);

        $EntryImg->createNameAndLinks($id,$file,$filename);
        if(!FileHelper::copy($file,$EntryImg->file,1))
            return false;

        $EntryImg->initFileInfo($EntryImg->file);
        $width = ($EntryImg->width > 1080) ? 1080 : 0;
        if(!self::optimize($EntryImg->file,$EntryImg->src1080,$width))
            return false;

        $newName = pathinfo($EntryImg->src1080, PATHINFO_BASENAME);
        qwe("REPLACE INTO images (id, old, new) VALUES (:id, :old, :new)",
        ['id'=>$id, 'old'=>$filename, 'new'=>$newName]
        );
        return true;
    }

    public static function oldName(int $id, string $newName) : string|bool
    {
        $qwe = qwe("SELECT * FROM images WHERE id = :id AND new = :new",['id'=>$id,'new'=>$newName]);
        if(!$qwe or $qwe->rowCount())
            return false;
        $q = $qwe->fetchObject();
        return $q->old;
    }

    public static function newName(int $id, string $oldName) : string|bool
    {
        $qwe = qwe("SELECT * FROM images WHERE id = :id AND `old` = :old",['id'=>$id,'old'=>$oldName]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $q = $qwe->fetchObject();
        return $q->new;
    }

}