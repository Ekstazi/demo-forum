<?php
namespace app\components;

use app\components\db\Db;

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // Этот код ошибки не включен в error_reporting
        return;
    }

    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>ERROR</b> [$errno] $errstr<br />\n";
            echo "  Фатальная ошибка в строке $errline файла $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Завершение работы...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>WARNING</b> [$errno] $errstr в строке $errline файла $errfile<br />\n";
            break;

        case E_USER_NOTICE:
            echo "<b>NOTICE</b> [$errno] $errstr в строке $errline файла $errfile<br />\n";
            break;

        case E_CORE_ERROR:
            echo "<b>Fatal error</b> [$errno] $errstr в строке $errline файла $errfile<br />\n";


        default:
            echo "Неизвестная ошибка: [$errno] $errstr в строке $errline файла $errfile<br />\n";
            break;
    }

    /* Не запускаем внутренний обработчик ошибок PHP */
    return true;
}

;

function fatal_handler()
{
    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];
        myErrorHandler($errno, $errstr, $errfile, $errline);
    }
}

set_error_handler(__NAMESPACE__ . '\\myErrorHandler');

register_shutdown_function(__NAMESPACE__ . "\\fatal_handler");

set_exception_handler(function (\Exception $e) {
    echo 'Uncaught ' . get_class($e) . ', code: ' . $e->getCode() . "<br />Message: " . htmlentities($e->getMessage()) . "\n";
    echo '<br/>';
    echo $e->getTraceAsString();
});

class App
{
    public $errorRoute = 'site/error';

    /**
     * @var Controller
     */
    public $controller;

    protected $settings;

    protected $components = [];

    protected static $instance;

    public function __construct()
    {
        $this->loadConfig();
        $this->registerAutoloader();
        mb_internal_encoding("UTF-8");
    }


    /**
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    protected function createComponent($componentName)
    {
        $options = $this->settings['components'][$componentName];
        if (!isset($options['class'])) {
            throw new \Exception('Unknown class for component: ' . $componentName);
        }

        $class = $options['class'];
        unset($options['class']);

        $component = new $class();
        foreach ($options as $property => $value) {
            $component->$property = $value;
        }
        return $component;
    }

    protected function getComponent($name)
    {
        if (isset($this->components[$name]))
            return $this->components[$name];

        return $this->components[$name] = $this->createComponent($name);
    }

    protected function setComponent($name, $value)
    {
        $this->components[$name] = $value;
    }


    public static function instance()
    {
        if (isset(self::$instance))
            return self::$instance;

        return self::$instance = new static();
    }

    /**
     * @return Db
     */
    public function getDb()
    {
        return $this->getComponent('db');
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->setComponent('db', $db);
    }

    /**
     * @return Request`
     */
    public function getRequest()
    {
        return $this->getComponent('request');
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->setComponent('request', $request);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->getComponent('user');
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->setComponent('user', $user);
    }

    /**
     * @return UrlManager
     */
    public function getUrlManager()
    {
        return $this->getComponent('urlManager');
    }

    /**
     * @param mixed $urlManager
     */
    public function setUrlManager($urlManager)
    {
        $this->urlManager = $urlManager;
        $this->setComponent('urlManager', $urlManager);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->getComponent('response');
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
        $this->setComponent('response', $response);
    }

    /**
     * @return View
     */
    public function getView()
    {
        return $this->getComponent('view');
    }

    /**
     * @param View $view
     */
    public function setView(View $view)
    {
        $this->setComponent('view', $view);
    }

    /**
     * @return Hasher
     */
    public function getHasher()
    {
        return $this->getComponent('hasher');
    }

    public function setHasher(Hasher $hasher)
    {
        $this->setComponent('hasher', $hasher);
    }

    /**
     * @return Mailer
     */
    public function getMailer()
    {
        return $this->getComponent('mailer');
    }

    public function setMailer(Mailer $mailer)
    {
        $this->setComponent('mailer', $mailer);
    }


    public function run()
    {
        try {
            $route = $this->parseRequest();
            $response = $this->runController($route);
            $this->getResponse()->process($response);
        } catch (NotFoundException $e) {
            $this->render404();
        }
    }

    protected function render404()
    {
        $this->runController($this->errorRoute);
    }

    protected function parseRequest()
    {
        return $this->getUrlManager()->parseRequest($this->getRequest());
    }

    protected function runController($route)
    {
        list($id, $action) = explode('/', $route);

        $controllerName = ucfirst($id) . 'Controller';
        $nsClassName = 'app\\controllers\\' . $controllerName;
        if (!class_exists($nsClassName)) {
            throw new NotFoundException('Cannot find controller: ' . $controllerName);
        }

        if (!is_subclass_of($nsClassName, '\\app\\components\\Controller')) {
            throw new NotFoundException('Controller must extends ' . __NAMESPACE__ . '\\Controller class');
        }
        $controller = new $nsClassName($id);

        $actionId = isset($action) ? $action : null;

        $this->controller = $controller;
        return $controller->runAction($actionId);
    }

    /**
     * @return mixed
     */
    protected function loadConfig()
    {
        $config = require_once($this->getBasePath() . '/config/main.php');
        $defaultSettings = [
            'components' => [
                'db'         => [
                    'class' => __NAMESPACE__ . '\db\Db',
                ],
                'request'    => [
                    'class' => __NAMESPACE__ . '\Request',
                ],
                'user'       => [
                    'class' => __NAMESPACE__ . '\User',
                ],
                'urlManager' => [
                    'class' => __NAMESPACE__ . '\UrlManager',
                ],
                'response'   => [
                    'class' => __NAMESPACE__ . '\Response',
                ],
                'view'       => [
                    'class' => __NAMESPACE__ . '\View',
                ],
                'hasher'     => [
                    'class' => __NAMESPACE__ . '\Hasher',
                ],
                'mailer'     => [
                    'class' => __NAMESPACE__ . '\Mailer',
                ],
            ],
        ];

        $this->settings = array_merge_recursive($defaultSettings, $config);
    }

    protected function registerAutoloader()
    {
        spl_autoload_register(function ($className) {
            $basePath = $this->getBasePath();

            $classLocalPath = strtr($className, ['\\' => '/', 'app\\' => '']);
            $fullPath = $basePath . '/' . $classLocalPath . '.php';

            if (file_exists($fullPath))
                require_once($fullPath);
        });
    }
}