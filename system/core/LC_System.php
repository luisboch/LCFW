<?php

lcSystemLoad("exceptions/UrlFilteredException.php");

class LC_System {

    /**
     *
     * @var LC_FilterLoader
     */
    private $_filter;

    /**
     *
     * @var LC_ViewLoader
     */
    private $_view;

    /**
     *
     * @var LC_System
     */
    private static $_system;

    /**
     *
     * @var array
     */
    private $contexts;

    private function __construct() {
        
    }

    /**
     * @return LC_System 
     */
    public static function getInstance() {
        return self::$_system == NULL ?
                self::$_system = new LC_System() : self::$_system;
    }

    public function load() {
        $this->_view = new LC_ViewLoader();
        $this->_filter = LC_FilterLoader::getInstance();
        $this->_filter->setSystem($this);
        $this->_autoLoad();
        $this->_loadRoute();
    }

    public function _autoLoad() {

        if (function_exists('scandir')) {
            $files = scandir(LC_AUTOLOAD_DIR);

            foreach ($files as $file) {
                if ($file != ".." && $file != '.' && $file != 'autoload.php')
                    require LC_AUTOLOAD_DIR . $file;
            }
        } else {
            require LC_AUTOLOAD_DIR . 'autoload.php';
        }
    }

    public function _loadRoute() {
        /* @var $classLoad array */

        $classLoad = $this->_filter->loadUrl();
        $this->loadController($classLoad['class']);
        $this->dispatch($classLoad['class'], $classLoad['method']);
    }

    public function dispatch($class, $method = 'index') {
        $reflectionClass = new ReflectionClass($class);
        try {
            $reflectionMethod = new ReflectionMethod($class, $method);
        } catch (ReflectionException $e) {
            $this->_view->show404();
        }
        if (array_search('ILC_Controller', $reflectionClass->getInterfaceNames()) === FALSE) {
            throw new InvalidArgumentException("Service must be instance of ILC_Controller!");
        }
        $instance = $reflectionClass->newInstance();
        $instance->setLoader($this->_view);
        $str = $reflectionMethod->invokeArgs($instance, $this->_filter->getExtraParams());
    }

    public function loadController($controller) {
        if (file_exists(LC_CONTROLLERS_DIR . $controller . '.php')) {
            lcImport($controller . '.php', LC_CONTROLLERS_DIR);
        } else {
            $this->_view->show404();
        }
    }

    /**
     * 
     * @return LC_FilterLoader
     */
    public function &getUrlFilter() {
        return $this->_filter;
    }

    public function getContexts() {
        return $this->contexts;
    }

    public function setContexts($contexts) {
        $this->contexts = $contexts;
    }

}

?>
