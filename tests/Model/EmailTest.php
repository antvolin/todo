<?php

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Model\Email;

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
     * @param string $email
     *
     * @dataProvider notValidEmails
     *
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
     */
    public function shouldBeNotConstructableWithNotValidEmail(string $email): void
    {
        $this->expectException(NotValidEmailException::class);

        new Email($email);
    }

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
