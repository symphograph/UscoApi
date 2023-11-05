<?php

namespace App\SDT\Order;

use App\SDT\BindTrait;
use App\SDT\Client;
use App\SDT\DB;
use PDO;

class Order implements OrderITF
{
    use BindTrait;
    const tableName = 'orders';
    const allowedStatuses = ['complete', 'new'];

    protected int $id;
    protected int $item_id;
    protected int $customer_id;
    protected string $comment;
    protected string $status;
    protected string $order_date;

    public static function byLine(string $line): self
    {
        $data = explode(';', $line);

        $order = new self();
        $order->item_id = $data[0];
        $order->customer_id = $data[1];
        $order->comment = $data[2];
        $order->status = $data[3];
        $order->order_date = $data[4];
        return $order;
    }

    public static function byID(int $id): self
    {
        $sql = "select * from orders where id = :id";
        $qwe = DB::qwe($sql, ['id' => $id]);
        return $qwe->fetchObject(self::class);
    }

    protected function putToDB(): void
    {
        DB::insertRow(self::tableName, $this->getAllProps());
    }


}