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
?>
<div class="w3-top w3-theme" id="userbar" >
    <a href="javascript:void(0)" onclick="acercaDe()" style="display:none"><img class="w3-left" style="margin-left: 2px" src="<?= $esculapio_ico ?>" width="20px" /></a>
    <span class="w3-tiny w3-red" id="userbar_men" style="margin-left: 10px"></span>
    <p class="w3-tiny w3-right"><i class="w3-medium w3-text-orange fa fa-user"></i>&nbsp;Usuario actual:
    <?php
    if(!isset($_SERVER['PHP_AUTH_USER']) or empty($_SERVER['PHP_AUTH_USER'])) {
        $userName = "usuario invitado";
    } else {
        $userName = \nuimsa\tools\getUserName($_SERVER['PHP_AUTH_USER']);
    }
    ?>
    <span><?= $userName ?></span></p>
</div>