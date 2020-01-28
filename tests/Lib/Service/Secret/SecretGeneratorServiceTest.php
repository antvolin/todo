<?php

namespace Tests\Lib\Service\Secret;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Service\Secret\SecretGeneratorService;

class SecretGeneratorServiceTest extends TestCase
{
    /**
     * @test
     */
    public function secretShouldBeGeneratedValidSecret(): void
    {
        $tokenSecretPrefix = App::getTokenSecretPrefix();
        $generator = new SecretGeneratorService($tokenSecretPrefix, App::getTokenSecret());
        $secretPrefix = uniqid($tokenSecretPrefix, true);
        $secret = $generator->generateSecret($secretPrefix);

        $this->assertEquals(md5(App::getTokenSecret().$secretPrefix), $secret);
    }
}
