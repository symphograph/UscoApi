<?php

namespace Tests\App\Fidback;

use App\Feedback\FormHandlerLax;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symphograph\Bicycle\Errors\EmailErr;

class FormHandlerLaxTest extends TestCase
{
    /**
     * @dataProvider emailDataProvider
     */
    public function testValidateEmail($email, $isValid)
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        // Создаем объект FormHandlerLax
        $formHandler = new FormHandlerLax($email, 'Test message');

        // Используем рефлексию для доступа к приватному методу validateEmail
        $reflectionMethod = new ReflectionMethod(FormHandlerLax::class, 'validateEmail');

        // Если ожидается, что email будет корректным, то не должно быть исключения
        if ($isValid) {
            $this->assertNull($reflectionMethod->invokeArgs($formHandler, []));
        }
        // Если ожидается, что email будет некорректным, то должно быть исключение EmailErr
        else {
            $this->expectException(EmailErr::class);
            $reflectionMethod->setAccessible(true);
            $reflectionMethod->invokeArgs($formHandler, []);
        }
    }

    public static function emailDataProvider(): array
    {
        return [
            ['valid@example.com', true],        // Корректный email
            ['another@example.com', true],     // Корректный email
            ['invalid_email', false],          // Некорректный email
            ['missing_at.com', false],         // Некорректный email
            ['no_dot@domaincom', false],       // Некорректный email
        ];
    }
}



