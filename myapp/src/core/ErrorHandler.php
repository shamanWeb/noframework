<?php

namespace App\core;

use Exception;
use Throwable;

class ErrorHandler
{
    /**
     * Init error handler
     */
    public function init(): void
    {
        ini_set('display_errors', 1);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @param Exception|Throwable $exception
     * @throws AppException
     */
    public function handleException(Throwable $exception): void
    {
        $view = new View();
        $view->layoutName = 'error';
        $view->renderPage($view->renderFile('exception.php', [
            'exception' => $exception,
        ]));
        exit();
    }
}