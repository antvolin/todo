<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Todo\Controller\AuthController;
use Todo\Lib\App;

class AuthControllerTest extends TestCase
{
    private string $token;
    private Request $request;
    private AuthController $controller;

    protected function setUp()
    {
        $app = new App();
        $this->request = $app->getRequest();
        $this->token = $app->createToken();
        $this->controller = new AuthController($app->createAuthService($this->request), $app->createTemplate());
    }

    /**
     * @test
     */
    public function shouldBeNotLoginIfNotAdmin(): void
    {
        $request = $this->request;
        $request->request->set('token', $this->token);
        $request->setMethod('POST');
        $response = $this->controller->login();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('The entered data is not correct!', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldBeLoginIfAdmin(): void
    {
        $request = $this->request;
        $request->request->set('token', $this->token);
        $request->request->set('user', App::getUser());
        $request->request->set('password', App::getPassword());
        $request->setMethod('POST');
        $response = $this->controller->login();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function shouldBeGettingLoginPage(): void
    {
        $request = $this->request;
        $request->request->set('token', $this->token);
        $response = $this->controller->login();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotContains('The entered data is not correct!', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldBeLogout(): void
    {
        $request = $this->request;
        $request->request->set('token', $this->token);
        $request->request->set('user', App::getUser());
        $request->request->set('password', App::getPassword());
        $request->setMethod('POST');
        $this->controller->login();
        $response = $this->controller->logout();

        $this->assertEquals('/entity/list', $response->getTargetUrl());
        $this->assertNull($request->getSession()->get(App::getUser()));
    }
}
