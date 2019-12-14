<?php

namespace BeeJeeMVC\Tests\HashGenerator;

use BeeJeeMVC\HashGenerator;
use PHPUnit\Framework\TestCase;

class HashGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGenerated(): void
    {
        $userName = 'test user name';
        $email = 'test@test.test';
        $text = 'test text';

        $key = (new HashGenerator)->generateHash($userName, $email, $text);

        $this->assertEquals(hash('md5', 'test user nametest@test.testtest text'), $key);
    }
}
