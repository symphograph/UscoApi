<?php

namespace App\Controllers;

use App\Errors\TicketErr;
use App\Ticket;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

class TicketCTRL extends \App\Ticket
{
    public static function reserve(): void
    {
        $id = intval($_POST['id'] ?? false)
            or throw new ValidationErr();

        qwe("START TRANSACTION");
        $ticket = Ticket::byId($id)
            or throw new TicketErr('Ticket does not exist', 'Билет не найден', 402);
        $ticket->isAvalible()
            or throw new TicketErr('Ticket is not avalible', 'Билет уже зарезервирован. Попробуйте другой.');
        $ticket->userId = User::getIdByJWT();
        $ticket->reservedAt = date('Y-m-d H:i:s');
        $ticket->putToDB();
        qwe("COMMIT");
        Response::success();
    }
}