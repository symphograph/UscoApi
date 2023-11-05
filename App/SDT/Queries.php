<?php

namespace App\SDT;

use App\SDT\Client\Client;
use App\SDT\Merchandise\Merchandise;
use PDO;
use Symphograph\Bicycle\Errors\MyErrors;

class Queries
{
    /**
     * Выбрать всех клиентов, которые не делали заказы в последние 7 дней
     * @param int $days #[PositiveInt]
     * @return Client[]
     */
    public static function listClientsWithoutOrdersInLastDays(int $days = 7): array
    {
        if($days < 1) throw new MyErrors('days mast be Positive');
        $qwe = DB::qwe("
            SELECT
                c.id,
                c.NAME 
            FROM
                clients c
                LEFT JOIN orders o ON c.id = o.customer_id 
            WHERE
                o.order_date IS NULL 
                OR o.order_date <= NOW() - INTERVAL $days DAY
            GROUP BY c.id
        ");
        return $qwe->fetchAll(PDO::FETCH_CLASS, Client::class);
    }

    /**
     * Выбрать клиентов, которые сделали больше всего заказов в магазине.
     *
     * @param int $limit #[PositiveInt]
     * @return Client[]
     */
    public static function listTopClients(int $limit = 5): array
    {
        if($limit < 1) throw new MyErrors('limit mast be Positive');
        $sql = "
            SELECT c.id, c.name
            FROM clients c
            LEFT JOIN orders o ON c.id = o.customer_id
            GROUP BY c.id
            ORDER BY COUNT(o.id) DESC
            LIMIT :limit";

        $stmt = DB::pdo()->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Client::class);
    }

    /**
     * Выбрать 10 клиентов, которые сделали заказы на наибольшую сумму.
     *
     * @param int $limit #[PositiveInt]
     * @return array
     */
    public static function listTopSpendingClients(int $limit = 10): array
    {
        if($limit < 1) throw new MyErrors('limit mast be Positive');
        $sql = "
            SELECT
                c.id,
                c.name
            FROM
                clients c
            LEFT JOIN
                orders o ON c.id = o.customer_id
            LEFT JOIN
                merchandise m ON o.item_id = m.id
            GROUP BY
                c.id, c.name
            HAVING
                SUM(m.price) > 0
            ORDER BY
                SUM(m.price) DESC
            LIMIT :limit";

        $stmt = DB::pdo()->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Client::class);
    }

    /**
     * Выбрать все товары, по которым не было доставленных заказов
     *
     * @return Merchandise[] массив объектов товаров
     */
    public static function listMerchandiseWithoutDeliveredOrders(): array
    {
        $qwe = DB::qwe("
        SELECT
            m.id,
            m.name
        FROM
            merchandise m
        LEFT JOIN
            orders o ON m.id = o.item_id
        WHERE
            o.id IS NULL OR o.status != 'complete'
        GROUP BY m.id, m.name");
        return $qwe->fetchAll(PDO::FETCH_CLASS, Merchandise::class);
    }
}