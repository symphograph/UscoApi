<?php

namespace App\Feedback;

use Symphograph\Bicycle\Errors\EmailErr;

final class FormHandlerStrict extends FormHandler
{
    const string subject = 'Строгая форма';
    const string method = 'POST';

    /**
     * Обрабатывает отправленную форму.
     *
     * - Проверяет метод запроса
     * - Валидирует введенный email с использованием стандартной функции
     * - Обрабатывает отправленное сообщение
     * ---
     * Если все данные корректны, отправляет письмо на указанный адрес.
     *
     * @return void
     */
    public static function processForm(): void
    {
        $Handler = new self($_POST['email'] ?? '', $_POST['message'] ?? '', self::subject, self::method);
        $Handler->validateEmail();
        $Handler->validateMessage();
        $Handler->sendMessage();
    }

    /**
     * Проверяет корректность email
     * @return void
     */
    private function validateEmail(): void
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailErr();
        }
    }

    private function validateMessage(): void
    {
        /*
        if(!str_contains($this->message, 'Пушкин')){
            throw new EmailErr('VIP reference is empty', 'Зачем нам Россия без Пушкина!');
        }
        */
    }


}