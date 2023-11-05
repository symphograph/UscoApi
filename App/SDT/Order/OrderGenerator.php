<?php

namespace App\SDT\Order;

use App\SDT\Helpers;
use Symphograph\Bicycle\Env\Server\ServerEnv;

/**
 * Генератор тестовых данных для заказов.
 */
final class OrderGenerator extends Order
{
    // Путь к файлу с заказами
    const relPath = '/tmp/orders.txt';

    // Вероятность ошибок при генерации данных
    const errProbPercent = 10;

    /**
     * Создает новый объект OrderGenerator.
     *
     * @param int $i      Номер заказа.
     * @param int $length Максимальное значение для случайных ID товаров и клиентов.
     */
    private function __construct(int $i, int $length)
    {
        $this->item_id = rand(1, $length);
        $this->customer_id = rand(1, $length);
        $this->comment = "Комментарий к заказу номер $i";
        $this->status = self::genStatus();
        $this->order_date = self::genOrderDate($i);
    }

    /**
     * Генерирует дату заказа.
     *
     * @param int $i Номер заказа.
     * @return string Дата заказа в формате 'Y-m-d'.
     */
    private static function genOrderDate(int $i): string
    {
        $time = strtotime("-$i days");
        if (rand(0, 100) < self::errProbPercent) {
            return date('Y-m-32', $time); // Неверная дата (32 день в месяце)
        }
        return date('Y-m-d', $time);
    }

    /**
     * Генерирует статус заказа.
     *
     * @return string Статус заказа.
     */
    private static function genStatus(): string
    {
        if (rand(0, 100) < self::errProbPercent) {
            return 'invalid'; // Неверный статус
        }
        return self::allowedStatuses[rand(0, count(self::allowedStatuses) - 1)];
    }

    /**
     * Создает файл с тестовыми данными заказов.
     *
     * @param int $length Количество заказов для генерации.
     */
    public static function createFile(int $length = 10): void
    {
        $lines = self::getLines($length);
        $data = implode('', $lines);
        $fullPath = self::getFullPath();

        Helpers::fileForceContents($fullPath, $data);
    }

    /**
     * Возвращает полный путь к файлу с заказами.
     *
     * @return string Полный путь к файлу с заказами.
     */
    public static function getFullPath(): string
    {
        return dirname(ServerEnv::DOCUMENT_ROOT()) . self::relPath;
    }

    /**
     * Генерирует строку данных заказа.
     *
     * @return string Строка с данными заказа.
     */
    private function getLine(): string
    {
        $props = get_object_vars($this);
        return implode(';', $props) . PHP_EOL;
    }

    /**
     * Генерирует массив строк данных заказов.
     *
     * @param int $length Количество заказов для генерации.
     * @return string[] Массив строк с данными заказов.
     */
    private static function getLines(int $length): array
    {
        $lines = [];
        for ($i = 1; $i <= $length; $i++) {
            $Order = new self($i, $length);
            $lines[] = $Order->getLine();
        }
        return $lines;
    }
}
