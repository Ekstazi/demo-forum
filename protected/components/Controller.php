<?php

namespace app\components;


use app\components\helpers\Url;

class Controller extends Component
{
    public $defaultAction = 'index';

    public $action;

    public $layout = 'layouts/main';

    public $pageTitle;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function runAction($actionName = null)
    {
        if (!$actionName) {
            $actionName = $this->defaultAction;
        }

        $methodName = 'action' . ucfirst($actionName);

        if (!method_exists($this, $methodName)) {
            throw new NotFoundException('Page not found: ' . $actionName);
        }
        $this->action = $actionName;
        if ($this->beforeAction()) {
            App::instance()->getView()->context = $this;

            $result = call_user_func([$this, $methodName], App::instance()->getRequest());
        } else {
            throw new NotFoundException();
        }
        $this->afterAction();
        return $result;
    }

    protected function beforeAction()
    {
        return true;
    }

    protected function afterAction()
    {

    }

    public function render($viewName, $params = [])
    {
        $content = $this->renderPartial($viewName, $params);
        return $this->renderPartial($this->layout, ['content' => $content]);
    }

    public function renderPartial($viewName, $params = [])
    {
        return App::instance()->getView()->render($viewName, $params);
    }

    protected function resolveViewFile($viewName)
    {
        if (strpos($viewName, '/') !== false)
            return $viewName;

        return $this->id . '/' . $viewName;
    }

    public function redirect($route, $params = [])
    {
        $url = Url::toRoute($route, $params);
        App::instance()->getResponse()->redirect($url);
    }

    /**
     * @param $name
     * @return db\Repository
     * @throws \Exception
     */
    protected function getRepository($name)
    {
        return App::instance()->getDb()->getRepository($name);
    }
}