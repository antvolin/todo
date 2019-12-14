<?php

namespace BeeJeeMVC;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class PageController
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

    public function list(): void
    {
        $repo = new TaskRepository();

        $adapter = new ArrayAdapter($repo->getList());
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(3);

        if ($this->request->get('p')) {
            $pagerfanta->setCurrentPage($this->request->get('p'));
        }

        $nbResults = $pagerfanta->getNbResults();
        $currentPageResults = $pagerfanta->getCurrentPageResults();

        $routeGenerator = function($page) {
            return '/?route=page/list&p='.$page;
        };

        $view = new DefaultView();
        $options = array('proximity' => 3);
        $html = $view->render($pagerfanta, $routeGenerator, $options);

        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($currentPageResults);

        include_once('list.html');
    }

    public function create(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if (!$_SESSION['admin']) {
                $userName = $this->request->get('user_name');
                $email = $this->request->get('email');
                $text = $this->request->get('text');

                try {
                    $taskRepo = new TaskRepository();
                    $content = 'Task #' . $taskRepo->create($userName, $email, $text) . 'created!';

                    include_once('created.html');
                } catch (InvalidArgumentException $exception) {
                    $error = $exception->getMessage();

                    include_once('form_create.html');
                }
            } else {
                $error = 'Insufficient rights for this operation!';

                include_once('form_create.html');
            }
        } else {
            include_once('form_create.html');
        }
    }

    public function edit(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if ($_SESSION['admin']) {
                $hash = $this->request->get('hash');
                $text = $this->request->get('text');

                $taskRepo = new TaskRepository();

                $taskRepo->edit($hash, $text);

                include_once('edited.html');
            } else {
                $error = 'Insufficient rights for this operation!';

                include_once('edited_error.html');
            }
        } else {
            $hash = func_get_args()[0];

            include_once('form_edit.html');
        }
    }

    public function done(): void
    {
        if ($_SESSION['admin']) {
            $taskRepo = new TaskRepository();

            $taskRepo->done(func_get_args()[0]);

            include_once('done.html');
        } else {
            $error = 'Insufficient rights for this operation!';

            include_once('done_error.html');
        }
    }
}
