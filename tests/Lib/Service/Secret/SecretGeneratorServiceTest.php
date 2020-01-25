<?php

namespace Tests\Lib\Service\Secret;

use Todo\Lib\App;
use Todo\Lib\Service\Secret\SecretGeneratorService;
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
