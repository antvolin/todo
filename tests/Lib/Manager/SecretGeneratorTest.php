<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\SecretGeneratorManager;
use PHPUnit\Framework\TestCase;

class SecretGeneratorManagerTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGeneratedValidSecret(): void
    {
        $app = new App();
        $generator = new SecretGeneratorManager($app->getTokenSecretPrefix(), $app->getTokenSecret());
        $secretPrefix = uniqid($app->getTokenSecretPrefix(), true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5($app->getTokenSecret().$secretPrefix), $secret);
    }
}
