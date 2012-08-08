<?php

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
    private $targetPatch;

    /**
     *
     * @var LC_System
     */
    private $_system;

    private function __construct() {
        $this->_filters = array();
    }

    public static function &getInstance() {
        return self::$_instance == NULL ? self::$_instance = new LC_FilterLoader() : self::$_instance;
    }

    /**
     * @return array 
     */
    public function loadUrl() {
        $this->_loadUrl();
        
        $aux['class'] = $this->sequences[0];
        $aux['method'] = $this->sequences[1];
        return $aux;
    }

    public function getRequestedUrl() {
        return $this->requestedUrl;
    }

    public function setRequestedUrl($requestedUrl) {
        $this->requestedUrl = $requestedUrl;
    }

    /**
     * @return string String contain url requested without host and parmans
     */
    private function _loadUrl() {
        echo '<pre>';
        $this->requestedUrl = $_SERVER['SCRIPT_NAME']; # returns somenthins like /path/file.php or /path/index.php or only /index.php
        
        

        foreach ($this->_filters as $filter) {
            if ($this->matches($filter)) {
                if (!$filter->check($this->requestedUrl)) {
                    return;
                }
            }
        }
        
        
        $requestedUrl = substr($_SERVER['PHP_SELF'], 1);
        
        $requestedUrl = str_replace($this->requestedUrl, '', preg_replace('/^'.LC_INDEX_FILE.'/', '', $requestedUrl));
        $sequences = explode('/', $requestedUrl);
        $aux = $sequences;
        $sequences = array();
        foreach($aux as $k => $sq){
            if($sq != 'index.php' && 'index.html'){
                $sequences[] = $sq;
            }
        }
        if (array_search($sequences[0], $this->_system->getContexts()) === FALSE) {
            
            $this->targetClass = $sequences[0];
            $this->targetMethod = $sequences[1];
            $this->targetPatch = '';
            $this->sequences = &$sequences;
        
        } else {
            
            $this->targetPatch = $sequences[0];
            $this->targetClass = $sequences[1];
            $this->targetMethod = $sequences[2];
            
            foreach ($sequences as $k => $v){
                if($k != 0){
                    $this->sequences [$k-1] = $v;
                }
            }
            
        }
        
        print_r($this->sequences);
        exit;
    }

    /**
     * @param ILC_Filter $filter
     * @return boolean
     */
    private function matches(ILC_Filter $filter) {
        $pathern = $filter->getPattern();
        //TODO criar forma de validação.
        return false;
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


}

?>
