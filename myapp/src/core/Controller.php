<?php

namespace App\core;

class Controller
{
    /**
     * Layout name
     * @var string
     */
    public $layout = 'main';

    /**
     * @var View
     */
    protected $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init controller
     */
    public function init(): void
    {
        $this->view = new View($this->layout);
    }

    /**
     * @param       $viewName
     * @param array $params
     * @throws AppException
     */
    public function render($viewName, array $params = []): void
    {
        $controllerName = mb_strtolower(App::$instance->controllerName);
        $viewFile = $controllerName . '/' . $viewName . '.php';
        $content = $this->view->renderFile($viewFile, $params);
        $this->view->renderPage($content);
    }

    /**
     * @param       $route
     * @param array $params
     */
    public function redirect($route, $params = []): void
    {
        header('Location: ' . Route::createUrl($route, $params));
        exit;
    }

    /**
     * Redirect to Home Page
     */
    public function goHome(): void
    {
        header('Location: /');
        exit;
    }

    /**
     * Default action
     */
    public function actionIndex(): void
    {
    }
}