<?php

namespace App\SDT;


class Helpers
{
    /**
     * - Сохраняет файл
     * - Если фала нет, создает его
     * - Если нет директории, создаёт её
     *
     * @param string $fullPath
     * @param string $data
     * @param int $permissions
     * @return int
     */
    public static function fileForceContents(string $fullPath, string $data, int $permissions = 0775): int
    {
        $cleanPath = self::cleanPath($fullPath);

        $dir = dirname($cleanPath);

        if (!is_dir($dir)) {
            mkdir($dir, $permissions, true)
            // or throw new MyErrors("Не удалось создать директорию: $dir");
            or die("Не удалось создать директорию: $dir");
        }

        $bytes = file_put_contents($cleanPath, $data);
        if($bytes === false){
            // throw new MyErrors("Ошибка при создании файла.");
            die('Ошибка при создании файла.');
        }
        return $bytes;
    }

    public static function cleanPath(string $path): string
    {
        $path = preg_replace('#/+#', '/', $path);
        return preg_replace('#/\.\./#', '/', $path);
    }

}