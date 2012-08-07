<?
    /**
     * define root folder of application 
     */
    define("ROOT_FOLDER",  __DIR__);
    
    /**
     * Load configurations 
     */
    $properties = parse_ini_file('lc.ini');
    
    
    /**
     *  Load Base of framework
     */
    require $properties['LC_SYSTEM_DIR'].'lc_base.php';
    
    $_lc = LC_System::getInstance();
    
    /**
     * Set configuration params 
     */
    $_lc->setProperties($properties);
    /**
     * Load Request 
     */
    $_lc->load();
    
    
?>