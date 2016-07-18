<?php

namespace app\components;


class View extends Component
{
    /**
     * @var Controller
     */
    public $context;

    public $viewVars;

    public function getTitle()
    {
        return $this->context->pageTitle;
    }

    public function setTitle($title)
    {
        return $this->context->pageTitle = $title;
    }

    public function render($viewName, $vars)
    {
        $viewFile = $this->resolveViewFile($viewName);
        return $this->renderFile($viewFile, $vars);
    }

    protected function resolveViewFile($viewName)
    {
        $viewFile = strpos($viewName, '/') !== false ? $viewName : ($this->context->id . '/' . $viewName);
        $fullPath = $this->getBasePath() . '/' . $viewFile . '.php';
        if (!file_exists($fullPath)) {
            throw new \Exception('Cannot find view file: ' . $viewName . ', full path: ' . $fullPath);
        }
        return $fullPath;
    }

    protected function renderFile($viewFile, $vars)
    {
        ob_start();
        $this->viewVars = $vars;
        extract($vars);
        require_once($viewFile);
        return ob_get_clean();
    }

    public function getBasePath()
    {
        return App::instance()->getBasePath() . '/views';
    }
}