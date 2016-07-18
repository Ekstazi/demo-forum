<?php

namespace app\components;


class Request extends Component
{
    public function get($keyName = null, $default = null)
    {
        if (!$keyName) {
            return $_GET;
        }

        return isset($_GET[$keyName]) ? $_GET[$keyName] : $default;
    }

    public function post($keyName = null, $default = null)
    {
        if (!$keyName) {
            return $_POST;
        }

        return isset($_POST[$keyName]) ? $_POST[$keyName] : $default;
    }

    public function getBaseUrl()
    {
        return rtrim(dirname($_SERVER['REQUEST_URI']), '/');
    }

    public function getServerName()
    {
        return $_SERVER['HTTP_HOST'];
    }
}