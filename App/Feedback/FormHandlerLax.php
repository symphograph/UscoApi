<?php

namespace App\Feedback;

use JetBrains\PhpStorm\Language;


final class FormHandlerLax extends FormHandler
{
    const string subject = 'Пермиссивная форма';
    const string method = 'GET';

    /**
     * Обрабатывает GET запрос.
     *
     * - Проверяет метод запроса
     * - Валидирует введенный email с использованием регулярного выражения
     * - Обрабатывает отправленное сообщение
     * ---
     * Если все данные корректны, отправляет письмо на указанный адрес.
     *
     * @return void
     */
    public static function processForm(): void
    {
        $Handler = new self($_GET['GET'] ?? '', $_GET['GET'] ?? '', self::subject, self::method);
        $Handler->validateEmail();
        $Handler->validateMessage();
        $Handler->sendMessage();
    }

    /**
     * Проверяет корректность email
     * - В качестве критерия используется регулярное выражение
     *
     * @param string $regExp
     * @return void
     */
    private function validateEmail(
        #[Language("RegExp")]
        string $regExp = '/@/'
    ): void
    {
        /*
        if (!preg_match($regExp, $this->email)) {
            throw new EmailErr('err');
        }
        */
    }

    private function validateMessage(): void
    {

    }
}