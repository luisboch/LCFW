<?php

class LC_System {

    /**
     *
     * @var LC_System
     */
    private static $_system;
    private static $properties;

    private function __construct() {
        
    }

    public static function getProperty($key) {
        return self::$_system->properties[$key];
    }

    /**
     * @return LC_System 
     */
    public static function &getInstance() {
        return self::$_system == NULL ?
                self::$_system = new LC_System() : self::$_system;
    }

    public static function setProperties($props) {
        self::getInstance()->properties = $props;
    }

    public function load() {
        $this->_autoLoad();
    }

    public function _autoLoad() {

        $autoloadDir = self::getProperty('LC_AUTOLOAD_DIR');

        if (function_exists('scandir')) {

            $files = scandir($autoloadDir);

            foreach ($files as $file) {
                if ($file != ".." && $file != '.' && $file != 'autoload.php')
               require $autoloadDir.$file;
            }
        }
        else{
            require $autoloadDir.'autoload.php';
        }
    }

}

?>
