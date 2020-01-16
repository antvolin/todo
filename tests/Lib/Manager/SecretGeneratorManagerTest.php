<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\SecretGeneratorService;
use PHPUnit\Framework\TestCase;

class SecretGeneratorManagerTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGeneratedValidSecret(): void
    {
        $app = new App();
        $tokenSecretPrefix = $app->getTokenSecretPrefix();
        $generator = new SecretGeneratorService($tokenSecretPrefix, $app->getTokenSecret());
        $secretPrefix = uniqid($tokenSecretPrefix, true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5($app->getTokenSecret().$secretPrefix), $secret);
    }
}
