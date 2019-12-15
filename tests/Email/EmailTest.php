<?php

namespace BeeJeeMVC\Tests\Email;

use BeeJeeMVC\Model\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConstructable(): void
    {
        $email = new Email('test@test.test');

        $this->assertEquals('test@test.test', $email);
    }

    /**
     * @test
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('');
    }

    /**
     * @test
     *
     * @param $email
     *
     * @dataProvider notValidEmails
     */
    public function shouldBeNotConstructableWithNotValidEmail($email): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($email);
    }

    /**
     * @return array
     */
    public function notValidEmails(): array
    {
        return [
            ['test'],
            ['test@'],
            ['t'],
            ['@test'],
        ];
    }
}
