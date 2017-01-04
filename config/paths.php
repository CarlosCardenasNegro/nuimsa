<?php
/**
 * NMT: The Nuclear Medicine Toolkit(tm) (http://www.nuimsa.es)
 * Copywright (c) San Miguel Software, Sl. (http://www.sanmiguelsoftware.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.TXT (https://www.tldrlegal.com/l/mit)
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copywright (c) San Miguel Software (http://www.sanmiguelsoftware.com)
 * @author      Carlos Cárdenas Negro 
 * @link        http://www.nuimsa.es
 * @since       0.1.0
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @version     2.0
 */
 
/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * The full path for use in HTML
 */
define('NUIMSA', '/nuimsa' . DS);

/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('APP_DIR', 'src/code');

/**
 * Path to class directory
 */
define('CLASS_DIR', 'src/clases');
 
/**
 * Path to the application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/**
 * File path to the webroot directory.
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/**
 * Path to the logs directory.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/**
 * Path to recursos directory
 */
define('RESOURCES', ROOT . DS . 'recursos' . DS);

/**
 * Path to the learning images directory
 */
define('IMAGES', ROOT . DS . 'learning' . DS . 'images' . DS);

/**
 * Path to the xml directory
 */
 define('XML', ROOT . DS . 'xml' . DS);