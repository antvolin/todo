<?php

namespace BeeJeeMVC\Tests\Lib\Service;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Service\SecretGeneratorService;
use PHPUnit\Framework\TestCase;

class SecretGeneratorServiceTest extends TestCase
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
