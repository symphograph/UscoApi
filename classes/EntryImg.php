<?php

class EntryImg extends Img
{
    public string|bool $error   = '';
    public string      $src1080 = '';
    public string      $src260 = '';
    public string      $srcOrig = '';

    public static function upload(array $FILE, bool $isPw = false): EntryImg
    {
        $EntryImg = new EntryImg();

        $er = Img::uplErors($FILE);
        if($er){
            $EntryImg->error = $er;
            return $EntryImg;
        }
        $id = intval($_POST['id'] ?? 0);
        if(!$id){
            $EntryImg->error = 'Ошибка id';
            return $EntryImg;
        }

        if(!$EntryImg->createNameAndLinks($id, $FILE['tmp_name'],$FILE['name'], $isPw)){
            $EntryImg->error = 'Ошибка при переименовании файла';
            return $EntryImg;
        }

        if($isPw)
            Entry::delPw($id);

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
        if(!$isPw)
            return new EntryImg($EntryImg->src1080);

        $width = ($EntryImg->width > 260) ? 260 : 0;
        if(!self::optimize($EntryImg->file,$EntryImg->src260,$width)){
            $EntryImg->error = 'Ошибка при сжатии';
            return $EntryImg;
        }
        return new EntryImg($EntryImg->src1080);
    }

    private function createNameAndLinks(int $id,string $file, string $filename,bool $isPw = false) : bool
    {
        if(!$id){
            return false;
        }
        $pw = $isPw ? '/pw/' : '/';

        $this->fileName = md5_file(FileHelper::addRoot($file));

        $this->ext = pathinfo($filename,PATHINFO_EXTENSION);
        $this->file = '/img/entry/origins/' . $id . $pw . $this->fileName . '.' . strtolower($this->ext);
        $this->src1080 = Entry::imgFolder . '/' . $id . $pw . $this->fileName . '.jpg';

        if($isPw)
            $this->src260 = '/img/entry/260/' . $id . $pw . $this->fileName . '.jpg';


        return true;
    }

    private function putToOriginals(string $from): bool
    {
        return FileHelper::moveUploaded($from,$_SERVER['DOCUMENT_ROOT'] . $this->file);
    }

}