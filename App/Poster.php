<?php

namespace App;

use App\Announce\Announce;
use Imagick;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\FileHelper;

class Poster extends Img
{
    public string $error   = '';
    public string $srcOrig = '';
    public string $folder  = '/img/posters';
    private bool  $isTopp  = false;

    public function __construct(bool $isTopp = false)
    {
        parent::__construct();
        if ($isTopp) {
            $this->folder = '/img/posters/topp';
            $this->isTopp = true;
        }

    }

    public static function byAnnounceId(int $id, bool $isTopp = false): Poster
    {
        $Poster = new Poster($isTopp);
        $Poster->fileName = 'poster_' . $id . '.jpg';
        $Poster->file = $Poster->folder . '/' . self::imgSizeOptimal() . '/' . $Poster->fileName;
        $Poster->verLink = Img::getVerLink($Poster->file);
        if ($Poster->verLink) {
            $Poster->initFileInfo($Poster->file);
            return $Poster;
        }

        if (!$Poster->findOrigin($id)) {
            //$Poster->verLink = Img::getVerLink('/img/afisha/empty.jpg');
            $Poster->initFileInfo('/img/afisha/empty.jpg');
            return $Poster;
        }

        if ($Poster->makeSizes()) {
            $Poster = Poster::byAnnounceId($id, $isTopp);
        }

        return $Poster;
    }

    private function findOrigin(int $id): bool
    {
        $exts = ['.jpg', '.jpeg', '.png'];
        foreach ($exts as $ext) {
            $path = $this->folder . '/origins/poster_' . $id . $ext;
            if (file_exists(ServerEnv::DOCUMENT_ROOT() . $path) && !is_dir(ServerEnv::DOCUMENT_ROOT() . $path)) {
                $this->srcOrig = $path;
                return true;
            }
        }
        return false;
    }

    private function makeSizes(): bool
    {
        $h1080 = $h480 = 0;
        if ($this->isTopp) {
            $h1080 = 684;
            $h480 = 304;
        }
        if (!self::makeSize(1080, $h1080))
            return false;

        if (!self::makeSize(480, $h480))
            return false;

        return true;
    }

    private function makeSize(int $with, int $height = 0): bool
    {
        $image = new Imagick(ServerEnv::DOCUMENT_ROOT() . '/' . $this->srcOrig);
        if (!$image->setImageFormat("jpeg"))
            return false;
        $image->stripimage();
        $image->setImageResolution(72, 72);
        $image->resampleImage(72, 72, \Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($with, $height, 0, 1);

        $newFile = $this->folder . '/' . $with . '/' . pathinfo($this->srcOrig, PATHINFO_FILENAME) . '.jpg';
        if (!$image->writeImage(ServerEnv::DOCUMENT_ROOT() . $newFile)) {
            return false;
        }

        return true;
    }

    public static function upload(array $file, bool $isTopp = false): Poster
    {
        $Poster = new Poster($isTopp);

        $er = self::uplErors($file);
        if ($er) {
            $Poster->error = $er;
            return $Poster;
        }


        if (!$Poster->nameById($file)) {
            $Poster->error = 'Не вижу дату';
            return $Poster;
        }

        if (!$Poster->putToOriginals($file)) {
            $Poster->error = 'Ошибка при сохранении файла';
            return $Poster;
        }


        if (!$Poster->makeSizes()) {
            $Poster->error = 'Ошибка при конвертации';
            return $Poster;
        }

        if (!Announce::reCache(intval($_POST['id'] ?? 0))) {
            $Poster->error = 'Ошибка при кэшировании';
            return $Poster;
        }

        return $Poster;
    }

    private function nameById(array $file): bool
    {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            return false;
        }

        $this->ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        $this->fileName = 'poster_' . $id . '.' . $this->ext;
        return true;
    }

    private function putToOriginals(array $file): bool
    {
        $this->srcOrig = self::getSrcOrig($this->fileName);
        return @move_uploaded_file($file['tmp_name'], ServerEnv::DOCUMENT_ROOT() . $this->srcOrig);
    }

    private function getSrcOrig(string $fileName): string
    {
        return $this->folder . '/origins/' . $fileName;
    }

    public static function getSrc(string $id, bool $isTopp = false)
    {

        $folder = '/img/posters';
        if ($isTopp) {
            $folder = '/img/posters/topp';
        }
        $file = $folder . '/' . Img::imgSizeOptimal() . '/poster_' . $id . '.jpg';
        if (file_exists(ServerEnv::DOCUMENT_ROOT() . $file)) {
            // $file = '/img/afisha/deftop3.jpg';
            return Img::getVerLink($file);
        }
        return false;
    }

    public static function delPosters(int $id): void
    {
        FileHelper::delAllExtensions('/img/posters/480/poster_' . $id);
        FileHelper::delAllExtensions('/img/posters/1080/poster_' . $id);
        FileHelper::delAllExtensions('/img/posters/origins/poster_' . $id);
    }

    public static function delTopps(int $id): void
    {
        FileHelper::delAllExtensions('/img/posters/topp/480/poster_' . $id);
        FileHelper::delAllExtensions('/img/posters/topp/1080/poster_' . $id);
        FileHelper::delAllExtensions('/img/posters/topp/origins/poster_' . $id);
    }

}