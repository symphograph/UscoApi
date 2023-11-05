<?php

namespace App\SDT\Order;

use App\SDT\BindTrait;
use Symphograph\Bicycle\Errors\ValidationErr;
use function PHPUnit\Framework\isFalse;

class OrderValidator extends Order
{
    use BindTrait;
    public function valid(): void
    {
        $this->status();
        $this->date();
    }

    private function status(): void
    {
        in_array($this->status, self::allowedStatuses)
        or throw new ValidationErr('invalid status');
    }

    private function date(): void
    {
        $format = 'Y-m-d';
        date($format, strtotime($this->order_date)) === $this->order_date
        or throw new ValidationErr('invalid date');
    }
}