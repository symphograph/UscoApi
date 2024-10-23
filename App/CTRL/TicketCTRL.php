<?php

namespace App\CTRL;

use App\Announce\Announce;
use App\Errors\TicketErr;
use App\Ticket;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\Errors\Auth\AuthErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Token\AccessTokenData;


class TicketCTRL extends Ticket
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
        !Announce::isCompleted($ticket->announceId)
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

        $tokenData = new AccessTokenData(ServerEnv::HTTP_ACCESSTOKEN());
        $ticket = Ticket::byId($id)
        or throw new TicketErr();
        if($ticket->userId !== $tokenData->userId){
            throw new AuthErr();
        }
        if(Announce::isCompleted($ticket->announceId)){
            throw new TicketErr('Expired', 'Бронирование завершено');
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