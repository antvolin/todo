<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\Manager\SecretGeneratorManager;
use PHPUnit\Framework\TestCase;

class SecretGeneratorManagerTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGeneratedValidSecret(): void
    {
        $generator = new SecretGeneratorManager($_ENV['TOKEN_SECRET_PREFIX'], $_ENV['TOKEN_SECRET']);
        $secretPrefix = uniqid($_ENV['TOKEN_SECRET_PREFIX'], true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5($_ENV['TOKEN_SECRET'].$secretPrefix), $secret);
    }
}
