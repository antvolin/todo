<?php

namespace BeeJeeMVC;

use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $base;

    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->base = '/';
        $this->name = 'Page';
        $this->request = Request::createFromGlobals();
    }

    public function login(): void
    {
        if ('POST' === $this->request->getMethod()) {
            $user = $this->request->get('user');
            $password = (int) $this->request->get('password');

            if ('admin' === $user && 123 === $password) {
                $_SESSION['admin'] = true;

                $repo = new TaskRepository();
                $builder = new Builder($this->name, $this->base);
                $content = $builder->buildList($repo->getList());

                include_once('list.html');
            } else {
                $error = 'The entered data is not correct!';

                include_once('login.html');
            }
        } else {
            include_once('login.html');
        }
    }

    public function logout(): void
    {
        if ($_SESSION['admin']) {
            unset($_SESSION['admin']);
        }

        $repo = new TaskRepository();
        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($repo->getList());

        include_once('list.html');
    }
}
