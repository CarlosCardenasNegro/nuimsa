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

/**
 * Pantalla de bienvenida a la app
 */
$title = "&nbsp;The Nuclear Medicine Toolkit";
$titleClass = "w3-center w3-jumbo";
$banner = "Utilidades y recursos para la Medicina Nuclear";
$banner_Class = "w3-container";
$esculapio  = NUIMSA  . 'webroot/img/atomo_esculapio2.svg';
?>
<div id="banner">
    <p class="w3-lato w3-center w3-jumbo"><i class="w3-jumbo fa fa-medkit"></i><?= $title ?></p>
    <p class="w3-lato w3-center w3-xlarge"><?= $banner ?></p>    
    <div>
        <img src="<?= $esculapio ?>" width="100%" alt="Fusión de la física nuclear y la medicina"/>
    </div>
</div>
<!-- INICIO DE LAS PAGINAS INFORMATIVAS  -->
<?php require_once APP . "info.php" ?>
<!-- FIN DE LAS PAGINAS INFORMATIVAS -->
<script>
$(function() {
    $(' #banner div').animate({top: '-230px', opacity: '.3'}, 1000);    
    $(window).scroll(function() {
      $(".slideanim").each(function(){
        var pos = $(this).offset().top;
        var winTop = $(window).scrollTop();
        if (pos < winTop + 800) { $(this).addClass("slide");}
      });
  });
});
</script>