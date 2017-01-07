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
 * Session start
 */
session_start();
 
require 'config' . DIRECTORY_SEPARATOR . 'paths.php';

/**
 * Save constants
 */
$_SESSION['DS']         = DS;
$_SESSION['ROOT']       = ROOT;
$_SESSION['NUIMSA']     = NUIMSA;
$_SESSION['APP_DIR']    = APP_DIR;
$_SESSION['APP']        = APP;
$_SESSION['CONFIG']     = CONFIG;
$_SESSION['WWW_ROOT']   = WWW_ROOT;
$_SESSION['LOGS']       = LOGS;
$_SESSION['RESOURCES']  = RESOURCES;
$_SESSION['IMAGES']     = IMAGES;
$_SESSION['XML']        = XML;

/**
 * Actual user (authentication)
 */
$_SESSION['username']       = 'Usuario invitado';

/**
 * Run the app
 */
require WWW_ROOT . 'index.php';
