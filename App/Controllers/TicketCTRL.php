<?php

namespace App\Controllers;

use App\Announce\Announce;
use App\Errors\TicketErr;
use App\Ticket;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AuthErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Token\AccessTokenData;


class TicketCTRL extends \App\Ticket
{
    public static function reserve(): void
    {
        $id = intval($_POST['id'] ?? false)
            or throw new ValidationErr();

        qwe("START TRANSACTION");
        $ticket = Ticket::byId($id)
            or throw new TicketErr('Ticket does not exist', 'Билет не найден', 404);
        $ticket->isAvalible()
            or throw new TicketErr('Ticket is not avalible', 'Билет уже зарезервирован. Попробуйте другой.', 403);
        !Announce::isComplited($ticket->announceId)
            or throw new TicketErr('complited', 'Время истекло', 403);
        $ticket->userId = User::getIdByJWT();
        $ticket->reservedAt = date('Y-m-d H:i:s');
        $ticket->putToDB();
        qwe("COMMIT");
        Response::success();
    }

    public static function returnTicket(): void
    {
        $id = intval($_POST['id'] ?? false)
        or throw new ValidationErr();

        $tokenData = new AccessTokenData();
        $ticket = Ticket::byId($id)
        or throw new TicketErr();
        if($ticket->userId !== $tokenData->userId){
            throw new AuthErr();
        }
        if(Announce::isComplited($ticket->announceId)){
            throw new TicketErr('Expired', 'Билет просрочен');
        }
        $ticket->unsetUser();
        $ticket->putToDB();
        Response::success();
    }

    public static function updateUserId(): void
    {
        User::auth([1]);
        $oldId = intval($_POST['oldId'] ?? false)
            or throw new ValidationErr();

        $newId = intval($_POST['newId'] ?? false)
        or throw new ValidationErr();

        qwe("
            update tickets 
            set userId = :newId,
                hasAccount = 1
            where userId = :oldId
            and hasAccount = 0",
            ['newId'=>$newId, 'oldId' => $oldId]
        );
        Response::success();
    }
}