<?php
lcSystemLoad('interfaces/ILC_Filter.php');
/**
 * Description of Loader
 *
 * @author luis.boch
 */
class LC_FilterLoader {

    /**
     * @var LC_FilterLoader
     */
    private static $_instance;

    /**
     *
     * @var array
     */
    private $_filters;
    private $requestedUrl;
    private $sequences;
    private $parameters;
    private $targetPatch;
    private $targetClass;
    private $targetMethod;

    /**
     *
     * @var LC_System
     */
    private $_system;

    private function __construct() {
        $this->_filters = array();
    }
    /**
     * 
     * @return LC_FilterLoader
     */
    public static function getInstance() {
        return self::$_instance == NULL ? self::$_instance = new LC_FilterLoader() : self::$_instance;
    }

    /**
     * @return array 
     */
    public function loadUrl() {
        $this->_loadUrl();
        if ($this->targetClass == '') {
            $this->targetClass = LC_DEFAULT_CONTROLLER;
        }
        if ($this->targetMethod == '') {
            $this->targetMethod = 'index';
        }

        $aux['method'] = $this->targetMethod;
        $aux['class'] = $this->targetClass;

        return $aux;
    }

    public function getRequestedUrl() {
        return $this->requestedUrl;
    }

    public function setRequestedUrl($requestedUrl) {
        $this->requestedUrl = $requestedUrl;
    }

    /**
     * @return string String contain url requested without host and pararams
     */
    private function _loadUrl() {

        $this->parameters = array();

        $this->requestedUrl = $_SERVER['SCRIPT_NAME']; # returns somenthins like /path/file.php or /path/index.php or only /index.php

        

        $requestedUrl = substr($_SERVER['PHP_SELF'], 1);
        
        foreach ($this->_filters as $filter) {
            
            if ($this->matches($filter,$requestedUrl)) {
                if (!$filter->check($requestedUrl)) {
                    exit;
                }
            }
        }
        if (LC_INDEX_FILE != '') {
            $requestedUrl = str_replace($this->requestedUrl, '', preg_replace('/^' . LC_INDEX_FILE . '/', '', $requestedUrl));
        }
        
        $aux = explode('/', $requestedUrl);
        $sequences = array();
        foreach ($aux as $k => $sq) {
            if ($sq != 'index.php' && $sq != 'index.html' && $sq != '' && $sq != LC_IGNORE_PATH) {
                $sequences[] = $sq;
            }
        }

        if(!isset($sequences[0])){
            $sequences[0] = '';
        }
        
        if (array_search($sequences[0], $this->_system->getContexts()) === FALSE) {
            if (isset($sequences[0])) {
                $this->targetClass = $sequences[0];
            }
            if (isset($sequences[1])) {
                $this->targetMethod = $sequences[1];
            }
            $this->targetPatch = '';
            $this->sequences = &$sequences;
        } else {
            if (isset($sequences[0])) {
                $this->targetPatch = $sequences[0];
            }
            if (isset($sequences[1])) {
                $this->targetClass = $sequences[1];
            }
            if (isset($sequences[2])) {
                $this->targetMethod = $sequences[2];
            }
            foreach ($sequences as $k => $v) {
                if ($k > 0 && $v != '') {
                    $this->sequences [$k - 1] = $v;
                }
            }
        }
        foreach ($this->sequences as $k => $v) {
            if ($k > 1 && $v != '') {
                $this->parameters [] = $v;
            }
        }
    }

    /**
     * @param ILC_Filter $filter
     * @return boolean
     */
    private function matches(ILC_Filter $filter, $url) {
        return preg_match($filter->getPattern(), $url) > 0;
    }

    public function getTargetClass() {
        return $this->targetClass;
    }

    public function setTargetClass($TargetClass) {
        $this->targetClass = $TargetClass;
    }

    public function getTargetMethod() {
        return $this->targetMethod;
    }

    public function setTargetMethod($TargetMethod) {
        $this->targetMethod = $TargetMethod;
    }

    public function setSystem(LC_System &$_system) {
        $this->_system = $_system;
    }

    /**
     * @return array of extra parameters
     */
    public function getExtraParams() {
        return $this->parameters;
    }

    public function setFilters($filters) {
        $this->_filters = $filters;
    }


}

?>
