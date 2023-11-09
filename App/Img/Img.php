<?php

namespace App\Img;

use Imagick;
use ImagickException;
use ImagickPixel;
use Symphograph\Bicycle\DTO\BindTrait;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\FileHelper;

class Img
{
    use BindTrait;

    public string $md5      = '';
    public bool   $exist    = false;
    public int    $width    = 0;
    public int    $height   = 0;
    public string $verLink  = '';
    public string $fileName = '';
    public string $ext      = '';
    private string $fullPath = '';


    public function __construct(public string $relPath = '')
    {
        if(empty($relPath)){
            return;
        }

        $this->relPath = str_replace('%20', ' ', $this->relPath);
        $this->fullPath = FileHelper::fullPath($this->relPath);

        self::initFileInfo();
    }

    protected function initFileInfo(): void
    {
        $this->fileName = basename($this->fullPath);
        $this->ext = pathinfo($this->fullPath, PATHINFO_EXTENSION);
        $this->exist = FileHelper::fileExists($this->fullPath);
        if (!$this->exist) {
            return;
        }

        $this->verLink = self::versioner();
        $this->initSize();
    }

    private function initSize(): void
    {
        $fullPath = FileHelper::fullPath($this->relPath);

        try {
            $image = new Imagick($fullPath);

            $this->width = $image->getImageWidth();
            $this->height = $image->getImageHeight();
            return;
        } catch (ImagickException $e) {
            return;
        }
    }

    private function versioner(): string
    {
        if (!$this->exist) {
            return '';
        }

        $this->md5 = md5_file($this->fullPath);

        return $this->relPath . '?ver=' . $this->md5;
    }

    public static function getVerLink(string $relPath): string
    {
        return (new Img($relPath))->verLink;
    }

    public static function uplErrors(array $file): string|bool
    {
        return match (true) {

            !empty($file['error']),

                empty($file['tmp_name']),

                $file['tmp_name'] == 'none'

                => 'Не удалось загрузить файл.',

            empty($file['name']),

                !is_uploaded_file($file['tmp_name'])

                => 'Ошибка при загрузке.',

            !self::isImage($file['tmp_name'])

                => 'Недопустимый формат изображения.',

            $file['size'] > 50000000

                => 'Файл слишком большой.',

            default => false
        };
    }

    public static function isImage(string $file): bool|string
    {
        $img_types = [
            0  => 'SVG',
            1  => 'GIF',
            2  => 'JPEG',
            3  => 'PNG',
            4  => 'SWF',
            5  => 'PSD',
            6  => 'BMP',
            7  => 'TIFF_II',
            8  => 'TIFF_MM',
            9  => 'JPC',
            10 => 'JP2',
            11 => 'JPX',
            12 => 'JB2',
            13 => 'SWC',
            14 => 'IFF',
            15 => 'WBMP',
            16 => 'XBM',
            17 => 'ICO',
            18 => 'webp'
        ];
        $is = @getimagesize($file);

        if (!$is)
            return false;

        if (!key_exists($is[2], $img_types))
            return false;

        return strtolower($img_types[$is[2]]);

    }

    public static function getOptimalSize(): int
    {
        $agent = get_browser();
        if ($agent->ismobiledevice) {
            return 1080;
        }

        return 480;
    }

    public static function optimize(string $from, string $to, int $width = 0, int $height = 0): bool
    {
        try {
            $image = new Imagick(ServerEnv::DOCUMENT_ROOT() . '/' . $from);
            $data = $image->identifyImage();
            if ($data['mimetype'] == 'image/png') {
                //printr($data);
                $image->setBackgroundColor(new ImagickPixel('transparent'));
                $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            }
            if (!$image->setImageFormat("jpeg"))
                return false;
            $image->stripimage();
            $image->setImageResolution(96, 96);
            $image->resampleImage(96, 96, \Imagick::FILTER_LANCZOS, 1);

            if ($width || $height) {
                $image->resizeImage($width, $height, 0, 1);
            }
            $image->setCompression(Imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(70);
            FileHelper::forceDir($to);
            $image->writeImage(ServerEnv::DOCUMENT_ROOT() . '/' . $to);

        } catch (ImagickException $e) {
            return false;
        }
        return true;
    }

}