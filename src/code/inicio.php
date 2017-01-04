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
 * Inicio.php - Pantalla principal de la app.
 */
$title = "&nbsp;The Nuclear Medicine Toolkit";    
$banner = "Recursos para la medicina nuclear";    
/**
 * Load constants...
 */
 if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
}
?>
<style>
#inicio { position: relative; top: 250px; opacity: .01; }
#inicio p { margin: 5px; background: rgba(0,0,0,.4) }
.w3-dialog { position:relative; width: 98%; height: 35em; margin-top: 6%; background: none }
#learning  { position: relative; top: 2%; height: 98%; display: none }
#learning div { height: 65%; overflow: auto }
#carrousel { display:none }
/* especial para la entrada solo */
#inicio { position: relative; top: 250px; opacity: .01; }
#inicio p { margin: 5px; background: rgba(0,0,0,.4) }
</style>
<div class="w3-container w3-theme" style="padding:0px; background: url('webroot/img/banner.jpeg') center no-repeat">
    <div id="inicio" class="w3-container">
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 5em"><i class="w3-jumbo fa fa-medkit"></i><?= $title ?></p>
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 2.5em"><?= $banner ?></p>
    </div>   
    <!-- CARROUSEL -->
    <div id="contenido" style="margin-bottom: 10em"></div>
</div>
<!-- INICIO DE LAS PAGINAS INFORMATIVAS  -->
<?php require_once APP. "info.php" ?>
<!-- FIN DE LAS PAGINAS INFORMATIVAS -->
<script>
// efecto al cargar la página (slideUp)
$( function() { $( ' #inicio' ).animate({top: '50px', opacity: '1'}, 1000); });
</script>
