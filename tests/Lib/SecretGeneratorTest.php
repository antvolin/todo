<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\SecretGenerator;
use PHPUnit\Framework\TestCase;

class SecretGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGeneratedValidSecret(): void
    {
        $generator = new SecretGenerator($_ENV['TOKEN_SECRET_PREFIX'], $_ENV['TOKEN_SECRET']);
        $secretPrefix = uniqid($_ENV['TOKEN_SECRET_PREFIX'], true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5($_ENV['TOKEN_SECRET'].$secretPrefix), $secret);
    }
}
