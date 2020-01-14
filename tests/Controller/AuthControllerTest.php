<?php

namespace BeeJeeMVC\Tests\Controller;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Lib\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthControllerTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var AuthController
     */
    protected $controller;

    protected function setUp()
    {
        $this->app = new App();

        $this->controller = new AuthController($this->app->getAuthService($this->app->getRequest()), $this->app->getTemplate());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeNotLoginIfNotAdmin(): void
    {
        $request = $this->app->getRequest();
        $request->request->set('token', $this->app->getToken());
        $request->setMethod('POST');
        $response = $this->controller->login();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('The entered data is not correct!', $response->getContent());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeLoginIfAdmin(): void
    {
        $request = $this->app->getRequest();
        $request->request->set('token', $this->app->getToken());
        $request->request->set('user', 'admin');
        $request->request->set('password', $_ENV['PASSWORD']);
        $request->setMethod('POST');
        $response = $this->controller->login();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeGettingLoginPage(): void
    {
        $request = $this->app->getRequest();
        $request->request->set('token', $this->app->getToken());
        $response = $this->controller->login();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotContains('The entered data is not correct!', $response->getContent());
    }

    /**
     * @test
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function shouldBeLogout(): void
    {
        $request = $this->app->getRequest();
        $request->request->set('token', $this->app->getToken());
        $request->request->set('user', 'admin');
        $request->request->set('password', $_ENV['PASSWORD']);
        $request->setMethod('POST');
        $this->controller->login();
        $response = $this->controller->logout();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
        $this->assertNull($request->getSession()->get('admin'));
    }
}
