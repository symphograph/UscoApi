<?php
namespace App\SDT\Client;

use App\SDT\BindTrait;

class Client
{
    use BindTrait;
    const tableName = 'clients';

    protected int $id;
    protected string $name;
}
