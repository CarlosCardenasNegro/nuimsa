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

/**
 * Menú simplificado para no profesionales de la app
 */
 
// recopilo textos largos 
$medicinaTXT        = "Información acerca de la especialidad";
$documentosTXT      = "Documentos informativos sobre los procedimientos y demás";
$consentimientosTXT = "Consentimientos informados habituales";
$proyeccionesTXT    = "Exploraciones más usuales en función de la patología y hallazgos";
$profesionalesTXT   = "acceso a profesionales";

$ul_class = "w3-navbar w3-theme-d1 w3-left-align w3-tiny w3-text-light-grey";
$li_class = "w3-hide-medium w3-hide-large w3-opennav w3-right";
$a_class = "w3-text-light-grey w3-hover-none w3-padding-large w3-hover-text-red";

?>

<div id="menubar" class="w3-top w3-card-4" >
    <ul class="<?= $ul_class ?>">
        <li class="w3-hide-medium w3-hide-large w3-opennav w3-right">
            <a class="w3-padding-large" href="javascript:void(0);" onclick="myFunction('navsmall')" title="Activa el menu de navegación"><i class="fa fa-bars"></i></a>
        </li>
        
        <li>
            <img src="<?= $esculapio_ico ?>" width="40px" style="position:relative; top: 4px; left: 5px"/>
        </li>
            
        <span class="w3-hide-small">
            <li class="w3-dropdown-hover w3-hover-none w3-theme-d1">
                <a class="<?= $a_class ?>" href="javascript:void(0)" title="<?= $medicinaTXT ?>">MEDICINA NUCLEAR&nbsp;<i class="fa fa-caret-down"></i></a>
                <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                    <a class="w3-hover-none" href="#pag1">Que es la medicina nuclear.?</a>
                    <a class="w3-hover-none" href="#pag2">Dos clases de medicina nuclear</a>
                </div>
            </li>
    
            <li class="w3-dropdown-hover w3-hover-none">
                <a class="<?= $a_class ?>" href="javascript:void(0)"  title="<?= $documentosTXT ?>">INFORMACION&nbsp;<i class="fa fa-caret-down"></i></a>
                <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                    <a class="w3-hover-none" href="javascript:void(0)" onclick="cargaPag('consentimiento.php')" title="<?= $consentimientosTXT ?>">Consentimientos</a>
                    <a class="w3-hover-none" href="javascript:void(0)" onclick="cargaPag('proyecciones.php')" title="<?= $proyeccionesTXT ?>">Proyecciones</a>
                    <a class="w3-hover-none" href="javascript:void(0)" onclick="cargaInfo('cisterno.xml')" title="<?= $proyeccionesTXT ?>">Exploraciones</a>                    
                </div>
            </li>
        
            <li class="w3-right">
                <a href="#pag3" class="w3-padding-large w3-hover-red"><i class="fa fa-question-circle-o"></i></a>
            </li>

            <li class="w3-right">
                <a class="w3-padding-large w3-hover-red" href="javascript:void(0)"  onclick="accesoProfesionales()" title="<?= $profesionalesTXT ?>">PROFESIONALES</a>
            </li>
        </span>
    </ul>
</div>

<!-- Navbar on small screens -->
<div id="navsmall" class="w3-hide w3-hide-large w3-hide-medium w3-top w3-card-4">
  <ul class="w3-navbar w3-left-align w3-theme-d1 w3-tiny">
    <!-- <li><a class="w3-padding-large" href="javascript:void(0)" onclick="cargaPag('inicio.html')">INICIO</a></li> -->
    <li><a class="w3-padding-large" href="javascript:void(0)" onclick="cargaPag('consentimiento.php')">CONSENTIMIENTOS</a></li>
    <li><a class="w3-padding-large" href="javascript:void(0)" onclick="cargaPag('proyecciones.php')">PROYECCIONES</a></li>
    <li><a class="w3-padding-large" href="javascript:void(0)" onclick="cargaPag('proyecciones.php')">PROFESIONALES</a></li>
</ul>
</div>
<script>
$( function() { 
    $( 'a[href^="#"]' ).on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 74
            }, 1000);
        }
    });
});
</script>