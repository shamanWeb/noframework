<?php

namespace App\core;

class View
{
    /**
     * @var string
     */
    public $layoutName = 'main';

    /**
     * @var string
     */
    protected $layoutsPath = APP_PATH . 'views/layouts/';

    /**
     * @var string
     */
    protected $viewsPath = APP_PATH . 'views/';

    /**
     * View constructor.
     * @param string $layout
     */
    public function __construct($layout = null)
    {
        $this->layoutName = $layout;
    }

    /**
     * @param string $viewFile
     * @param array  $params
     * @return string
     * @throws AppException
     */
    public function renderFile($viewFile, array $params = []): string
    {
        $viewFileName = $this->viewsPath . $viewFile;

        if (!file_exists($viewFileName)) {
            throw new AppException('View "' . $viewFile . '" is not found');
        }

        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }

        ob_start();
        require $viewFileName;
        return ob_get_clean();
    }

    /**
     * Layout + content
     * @param string $content
     * @throws AppException
     */
    public function renderPage($content = ''): void
    {
        $layoutFileName = $this->layoutsPath . $this->layoutName . '.php';

        if (!file_exists($layoutFileName)) {
            throw new AppException('Layout ' . $this->layoutName . ' is not found');
        }

        require_once $layoutFileName;
    }
}