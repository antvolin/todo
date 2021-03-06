<?php

namespace Tests\Lib\Service\Token;

use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Service\Token\TokenService;

class TokenServiceTest extends TestCase
{
    private string $secret;
    private string $token;
    private string $tokenSalt;
    private TokenService $tokenService;

    protected function setUp()
    {
        $app = new App();
        $this->tokenService = new TokenService();
        $this->secret = $app->createSecret();
        $this->tokenSalt = App::getTokenSalt();
        $this->tokenService->generateToken($this->secret, $this->tokenSalt);
        $this->token = $this->tokenService->getToken();
    }

    /**
     * @test
     */
    public function tokenShouldBeGenerated(): void
    {
        $this->assertEquals($this->tokenSalt.':'.md5($this->tokenSalt.':'.$this->secret), $this->token);
    }

    /**
     * @test
     */
    public function validationOfAValidTokenMustBeSuccessful(): void
    {
        $this->assertTrue($this->tokenService->isValidToken($this->token, $this->secret));
    }

    /**
     * @test
     */
    public function validationOfInvalidTokenMustFail(): void
    {
        $this->assertFalse($this->tokenService->isValidToken('not valid token', 'not valid secret'));
    }
}
