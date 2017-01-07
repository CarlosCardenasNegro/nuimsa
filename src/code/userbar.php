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
 * Load constants...
 */
if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
} 
?>
<div class="w3-top w3-theme" id="userbar" >
    <a onclick="acercaDe()"><img class="w3-left" src="<?= $esculapio_ico ?>" width="40px" /></a>
    <span class="w3-tiny w3-red" id="userbar_men" style="margin-left: 10px"></span>
    <p class="w3-medium w3-right"><i class="w3-large w3-theme-text fa fa-user"></i>&nbsp;<span><?= $_SESSION['username'] ?></span></p>
</div>