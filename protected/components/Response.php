<?php

namespace app\components;


class Response extends Component
{

    public function addCookie($name, $value = null, $expire = null)
    {
        // no js cookie
        setcookie($name, $value, $expire, null, null, null, true);
    }

    public function removeCookie($name)
    {
        $this->addCookie($name, null, -1);
    }

    public function redirect($uri)
    {
        header("Location: " . $uri);
        die();
    }

    public function process($result)
    {
        if (is_string($result)) {
            echo $result;
        } else if ($result instanceof self) {
            $result->send();
        }
    }

    public function send()
    {
        throw new \Exception('You must override this method in subclass');
    }
}