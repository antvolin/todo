<?php

namespace Tests\Lib\Traits;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Traits\TestValueGenerator;

class TestValueGeneratorTest extends TestCase
{
    use TestValueGenerator;

    /**
     * @test
     */
    public function shouldBeGeneratedEmail(): void
    {
        $email = $this->generateEmail();

        $this->assertIsString($email);
        $this->assertNotEmpty($email);
    }

    /**
     * @test
     */
    public function shouldBeGeneratedUserName(): void
    {
        $userName = $this->generateUserName('method', 'class');

        $this->assertIsString($userName);
        $this->assertNotEmpty($userName);
    }

    /**
     * @test
     */
    public function shouldBeGeneratedText(): void
    {
        $text = $this->generateUserName('method', 'class');

        $this->assertIsString($text);
        $this->assertNotEmpty($text);
    }
}
