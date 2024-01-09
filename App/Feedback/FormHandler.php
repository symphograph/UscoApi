<?php

namespace App\Feedback;

use JetBrains\PhpStorm\ExpectedValues;
use Symphograph\Bicycle\Env\Env;
use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Throwable;


/**
 * Класс для обработки формы обратной связи.
 *
 * - Принимает HTTP-запрос
 * - Обрабатывает данные формы
 * - Валидирует email
 * - Отправляет сообщение на указанный адрес.
 *
 * @package Feedback
 */
abstract class FormHandler implements FormHandlerITF
{

    const allowedMethods = ['POST', 'GET'];

    /**
     * Конструктор класса
     * @param string $email Email отправителя
     * @param string $message Сообщение отправителя
     * @param string $subject Тема сообщения
     * @param string $recipientEmail Адрес отправителя
     * @param string $expectedMethod Ожидаемый метод HTTP запроса
     */
    public function __construct(
        protected readonly string  $email,
        protected readonly string  $message,
        protected readonly string $subject,
        #[ExpectedValues(['POST', 'GET'])]
        protected string $expectedMethod,
        protected string  $recipientEmail = '',
    )
    {
        if(empty($recipientEmail)) {
            $this->recipientEmail = Env::getRecipientEmail();
        }
        $this->validateMethod(ServerEnv::REQUEST_METHOD());
        $this->noEmptyEmail();
        $this->noEmptyMessge();
    }

    /**
     * Отправляет сообщение электронной почты.
     *
     * @throws AppErr Если произошла ошибка при отправке письма.
     */
    protected function sendMessage(): void
    {
        try {
            $result = mail(
                $this->recipientEmail,
                $this->subject,
                $this->getSanitizedMessage(),
                $this->getHeadersString()
            );
        } catch (Throwable $err) {
            throw new AppErr($err->getMessage(), 'Произошла чудовищная ошибка');
        }
        if(!$result){
            throw new AppErr('error on send mail', 'Произошла ошибка');
        }
    }

    /**
     * Подготавливает входное сообщение к отправке по email:
     *
     * - Удаляет начальные и конечные пробелы из сообщения.
     * - Заменяет HTML-сущность амперсанда на сам символ амперсанда.
     * - Заменяет HTML-сущность неразрывного пробела на обычный пробел.
     * - Удаляет множественные пробелы, заменяя их на одиночные пробелы.
     * - Преобразует специальные символы в соответствующие HTML-сущности.
     *
     * @return string Подготовленное сообщение.
     */
    private function getSanitizedMessage(): string
    {
        $msg = trim($this->message);
        $msg = str_replace('&amp;', '&', $msg);
        $msg = str_replace('&nbsp;', ' ', $msg);
        $msg = preg_replace('/\s+/', ' ', $msg);

        return htmlspecialchars($msg);
    }

    /**
     * Проверяет, что email не пустой
     * @return void
     */
    private function noEmptyEmail(): void
    {
        if (empty($this->email)) {
            throw new ValidationErr(
                'email is empty',
                'Пожалуйста, введите email.'
            );
        }
    }

    /**
     * Проверяет, что сообщение не пустое
     * @return void
     */
    private function noEmptyMessge(): void
    {
        if (empty($this->message)) {
            throw new ValidationErr(
                'message is empty',
                'Пожалуйста, введите сообщение.'
            );
        }
    }

    /**
     * Формирует строку заголовков для отправки письма
     * @return string
     */
    protected function getHeadersString(): string
    {
        $headers = [
            'From'         => $this->email,
            'Reply-To'     => $this->email,
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=utf-8',
        ];

        $headersString = '';
        foreach ($headers as $key => $value) {
            $headersString .= "$key: $value\r\n";
        }

        return $headersString;
    }

    /**
     * Проверяет корректность метода запроса
     * @param $requestMethod
     * @return void
     */
    private function validateMethod($requestMethod): void
    {
        if (!in_array($requestMethod, self::allowedMethods)) {
            throw new ApiErr(
                message: 'invalid method',
                pubMsg: 'Федя, это не наш метод...',
                httpStatus: 405
            );
        }
    }
}