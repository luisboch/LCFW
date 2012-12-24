<?

/**
 * define root folder of application 
 */
define("ROOT_FOLDER", __DIR__);

/**
 * Load configurations 
 */
$properties = parse_ini_file('lc.ini');

/**
 * Check realpath of configurations
 */
foreach ($properties as $k => $v) {
    if (is_dir($v)) {

        $patch = realpath($v).'/';
    } else {
        $patch = $v;
    }
    define($k, $patch);
}

/**
 *  Load Base of framework
 */
require LC_SYSTEM_DIR . 'lc_base.php';

$_lc = LC_System::getInstance();

/**
 * Load Request 
 */
$_lc->load();
?>