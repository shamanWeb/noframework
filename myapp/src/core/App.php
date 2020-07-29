<?php

namespace App\core;

use mysqli;

class App
{
    /**
     * @var App
     */
    public static $instance;

    /**
     * @var string
     */
    public static $baseUrl;

    /**
     * @var mysqli
     */
    public static $db;

    /**
     * @var Session
     */
    public static $session;

    /**
     * @var array
     */
    public $config = [];

    /**
     * @var string
     */
    public $controllerName;

    /**
     * @var string
     */
    public $actionName;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    protected $controllerNamespace = 'App\controllers';

    /**
     * App constructor.
     * @param $config
     * @throws AppException
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->init();
    }

    /**
     * Init app
     * @throws AppException
     */
    public function init(): void
    {
        self::$instance = $this;
        self::$baseUrl = $this->config['baseUrl'];

        (new ErrorHandler())->init();

        self::$db = (new Database())->init($this->config['components']['db']);

        self::$session = new Session();
        self::$session->open();

        $this->user = new User();
        $this->user->init();

        // Defaults
        $this->controllerName = 'Site';
        $this->actionName = 'index';
    }

    /**
     * Run app
     * @throws AppException
     */
    public function run(): void
    {
        $route = Route::parseRequest();

        if (!empty($route[0])) {
            $this->controllerName = mb_convert_case($route[0], MB_CASE_TITLE, 'UTF-8');
        }

        if (!empty($route[1])) {
            $this->actionName = mb_convert_case($route[1], MB_CASE_TITLE, 'UTF-8');
        }

        $controllerClassName = $this->controllerNamespace . '\\' . $this->controllerName . 'Controller';

        if (!class_exists($controllerClassName)) {
            throw new AppException('Controller "' . $this->controllerName . '" not found in ' . $this->controllerNamespace);
        }

        $controller = new $controllerClassName;
        $actionName = 'action' . $this->actionName;

        if (!method_exists($controller, $actionName)) {
            throw new AppException('Action  "' . $this->actionName . '" not found in ' . $controllerClassName);
        }

        $controller->$actionName();
    }
}