<?php

namespace app\components;


class UrlManager extends Component
{
    public $defaultRoute = 'site/index';

    public function parseRequest(Request $request)
    {
        return $request->get('r', $this->defaultRoute);
    }

    public function createUrl($route, $params = [])
    {
        $params['r'] = $route;
        $url = 'index.php?' . http_build_query($params);
        $baseUrl = App::instance()->getRequest()->getBaseUrl();
        return $baseUrl . '/' . $url;
    }

    public function createAbsoluteUrl($route, $params = [])
    {
        return 'http://' . App::instance()->getRequest()->getServerName() . $this->createUrl($route, $params);
    }
}