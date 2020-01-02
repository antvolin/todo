<?php

namespace BeeJeeMVC\Tests\Model;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Model\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     */
    public function shouldBeConstructable(): void
    {
        $email = new Email('test@test.test');

        $this->assertEquals('test@test.test', $email);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     */
    public function shouldBeNotConstructableWithEmptyString(): void
    {
        $this->expectException(CannotBeEmptyException::class);

        new Email('');
    }

    /**
     * @test
     *
     * @param $email
     *
     * @dataProvider notValidEmails
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     */
    public function shouldBeNotConstructableWithNotValidEmail($email): void
    {
        $this->expectException(NotValidEmailException::class);

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
