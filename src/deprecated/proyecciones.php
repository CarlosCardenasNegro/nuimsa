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
 
require 'codigo.php';

/**
 * Proyecciones.php - Muestra algunos casos de interés,.. es obsoleta.
 */ 
$title = "&nbsp;Proyecciones";
$banner = "Algunas imágenes interesantes con las proyecciones adecuadas para un diagnóstico correcto.";
$dir = "./images/";
?>

<div class="w3-container">
    <!-- cabecera estándar -->
    <div id="inicio" class="w3-container">
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 4.5em"><i class="w3-jumbo fa fa-male"></i><?= $title ?></p>
        <p class="w3-lato w3-center w3-hide-medium w3-hide-small" style="font-size: 2.5em"><?= $banner ?></p>
    </div>
    <!-- cabecera estándar -->

    <!-- parte específica de cada página --> 
    
    <div class="w3container w3-margin-top w3-card-4 w3-blue w3-padding-xlarge">
        <h5 class="w3-xxlarge w3-center"><?php echo $casos["caso1"]["desc"]; ?></h5>
        <h5 class="w3-large"><?php echo $casos["caso1"]["explicacion"]; ?></h5>
  
        <div class="w3-container w3-white w3-text-red">
            <h5 class="w3-xlarge w3-center w3-padding-xlarge">Proyecciones obligatorias (mínimas)</h5>
        </div>
 
        <div class="w3-row w3-center">
            <div class="w3-quarter">
                <p class="w3-medium"><?php echo $casos["caso1"]["proyecciones"][0]; ?></p>
            </div>
            <div class="w3-quarter">
                <p class="w3-medium"><?php echo $casos["caso1"]["proyecciones"][1]; ?></p>
           </div>
            <div class="w3-quarter">
                <p class="w3-medium"><?php echo $casos["caso1"]["proyecciones"][2]; ?></p>
           </div>
            <div class="w3-quarter">
                <p class="w3-medium"><?php echo $casos["caso1"]["proyecciones"][3]; ?></p>
            </div>
        </div>

        <div class="w3-content w3-padding-large">
           <?php 
           $num_slides = count($casos["caso1"]["imagenes"]);
           $s = ($num_slides == 1 ? 12 : 6);
           $m = ($num_slides >= 3 ? 4  : 6);
           $l = ($num_slides >= 6 ? 2  : 6);
           
           for ($i = 0; $i < $num_slides; $i++): ?>
             <img class="mySlides" src="<?php echo $dir . $casos["caso1"]["imagenes"][$i]; ?>" style="width:60%; margin:auto">
           <?php endfor; ?>
    
            <div class="w3-row-padding w3-section">
                <?php for ($i = 0; $i < $num_slides; $i++): ?>
                <div class="w3-col s<?php echo $s; ?> m<?php echo $m; ?> l<?php echo $l; ?>">
                    <img class="demo w3-border w3-hover-shadow" src="<?php echo $dir . $casos["caso1"]["imagenes"][$i]; ?>" style="width:100%" onclick="currentDiv(<?php echo $i + 1; ?>)">
                </div>
                <?php endfor; ?>
            </div>
        </div>
</article>
<script>
var slideIndex = 1;
showDivs(slideIndex);
</script>