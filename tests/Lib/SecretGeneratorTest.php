<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\SecretGenerator;
use PHPUnit\Framework\TestCase;

class SecretGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGenerated(): void
    {
        $generator = new SecretGenerator();
        $secretPrefix = uniqid($_ENV['TOKEN_SECRET_PREFIX'], true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5($_ENV['TOKEN_SECRET'].$secretPrefix), $secret);
    }
}
