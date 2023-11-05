<?php

namespace App\SDT;

use App\SDT\Client\ClientList;
use App\SDT\Merchandise\MerchandiseList;
use App\SDT\Order\OrderGenerator;

class TestDataCreator
{
    public static function createData(int $length): void
    {
        self::createTables();
        self::createMerchandise($length);
        self::createClients($length);
        OrderGenerator::createFile($length);

    }

    private static function createTables(): void
    {
        DB::qwe("DROP TABLE IF EXISTS orders");
        DB::qwe("DROP TABLE IF EXISTS merchandise");
        DB::qwe("DROP TABLE IF EXISTS clients");

        DB::qwe("
            CREATE TABLE merchandise (
                id INT NOT NULL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL
            )"
        );

        DB::qwe("
            CREATE TABLE clients (
                id INT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )"
        );

        DB::qwe("
            CREATE TABLE orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                item_id INT NOT NULL,
                customer_id INT NOT NULL,
                comment TEXT NOT NULL,
                status VARCHAR(10) NOT NULL,
                order_date DATE NOT NULL,
                INDEX idx_status (status),
                INDEX idx_order_date (order_date),
                FOREIGN KEY (item_id) REFERENCES merchandise(id),
                FOREIGN KEY (customer_id) REFERENCES clients(id)
            )"
        );

    }

    private static function createMerchandise(int $length): void
    {
        $MerchandiseList = new MerchandiseList();
        $MerchandiseList->initGeneratedList($length);
        $MerchandiseList->putListToDB();
    }

    private static function createClients(int $length): void
    {
        $ClientList = new ClientList();
        $ClientList->initGeneratedList($length);
        $ClientList->putListToDB();
    }
}