<?php

namespace App\SDT\Order;

use App\SDT\BindTrait;
use App\SDT\Helpers;
use App\SDT\ListTrait;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\Errors\MyErrors;


class OrderList
{
    use BindTrait;
    use ListTrait;

    const tableName = 'orders';
    const errorRelPath = '/tmp/orderErr.txt';

    /**
     * @var Order[]
     */
    private array $list     = [];
    private array $invalids = [];


    public static function byFile(string $pathToFile = ''): self
    {
        $self = new self();
        $self->initFromFile();
        return $self;
    }

    private function initFromFile(string $pathToFile = ''): void
    {
        if(empty($pathToFile)){
            $pathToFile = OrderGenerator::getFullPath();
        }

        if(!file_exists($pathToFile)){
            throw new MyErrors('file does not exist');
        }

        $lines = file($pathToFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $invalids = [];
        foreach ($lines as $line) {
            try {
                $order = Order::byLine($line);
                OrderValidator::byBind($order)->valid();
                $this->list[] = $order;
            } catch (\Throwable $err) {
                $this->invalids[] = $line;
            }
        }

        if(!empty($this->invalids)) {
            $this->saveInvalids();
        }
    }

    private function saveInvalids(): void
    {
        $data = implode(PHP_EOL, $this->invalids);
        $errFullPath = dirname(ServerEnv::DOCUMENT_ROOT()) . self::errorRelPath;
        Helpers::fileForceContents($errFullPath, $data);
    }


}
