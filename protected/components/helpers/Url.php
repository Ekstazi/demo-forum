<?php
namespace app\components\helpers;

use app\components\App;

class Url
{
    public static function toRoute($route, $params = [])
    {
        $controller = App::instance()->controller;
        if ($route === '') {
            $route = $controller->id . '/' . $controller->action;
        }
        if (strpos($route, '/') === false) {
            $route = $controller->id . '/' . $route;
        }

        return App::instance()->getUrlManager()->createUrl($route, $params);
    }

    public static function to($routeParams)
    {
        $route = $routeParams[0];
        unset($routeParams[0]);
        return self::toRoute($route, $routeParams);
    }

    public static function base()
    {
        return App::instance()->getRequest()->getBaseUrl();
    }

    public static function absoluteTo($routeParams)
    {
        $route = $routeParams[0];
        unset($routeParams[0]);

        return self::absoluteToRoute($route, $routeParams);
    }

    public static function absoluteToRoute($route, $params = [])
    {
        return App::instance()->getUrlManager()->createAbsoluteUrl($route, $params);
    }

    public static function absoluteBase()
    {
        return 'http://' . App::instance()->getRequest()->getServerName() . '/' . self::base();
    }

    public static function home()
    {
        return self::toRoute(App::instance()->getUrlManager()->defaultRoute);
    }
}