<?php
/**
 * Nuclear Medicine Toolkit(tm) (http://www.nuimsa.es)
 * San Miguel Software, Sl. - Marzo/Mayo de 2016 (http://www.sanmiguelsoftware.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.TXT (https://www.tldrlegal.com/l/mit)
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  San Miguel Software - Marzo/Mayo de 2016 (http://www.sanmiguelsoftware.com)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * @author    Carlos Cárdenas Negro
 * @version   1.0
 * @link      (http://www.nuimsa.es)
 */
 
require_once 'codigo.php';

/**
 * Consentimientos.php - Muestra los consentimientos disponibles.
 *
 * Actualmente están en pdf pero la intención es pasarlos a html
 * (ver cisternogammagrafía), pero los datos recuperados de archivos
 * xml o mySQL,.. aún no lo se...?
 * 
 */ 
$title = "&nbsp;Consentimientos informados";
$banner = " CI para las exploraciones llevadas a cabo en Medicina Nuclear";
?>

<div class="w3-container">
    <!-- cabecera estándar -->
    <div id="inicio" class="w3-container">
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 4.5em"><i class="w3-jumbo fa fa-medkit"></i><?= $title ?></p>
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 2.5em"><?= $banner ?></p>
    </div>
    <!-- cabecera estándar -->
    
    <!-- parte específica de cada página --> 
    <div id="consentimientos">   
        <?= creaConsentimientos() ?>
    </div>
    <!-- parte específica de cada página --> 
    
</div>
<script>
/**
 * Script estándar en todas 
 * las páginas que se cargan.
 * Es un poco DRY,...pero,...
 * no tengo claro donde lo podría
 * poner,... ya lo pensaré
 *
 */
// efecto al cargar la página (slideUp)
$( function() {
   $( ' #inicio' ).animate({top: '0px', opacity: '1'}, 1000, function() {
       $(' #consentimientos' ).fadeTo(600,.9);
   }); 
});
</script>

