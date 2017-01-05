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
 * Información general acerca de la Medicina Nuclear
 *
 * 05/01/2017 Muestro las páginas informativas solo en banner.php
 *
 */

/**
 * Load constants...
 */
 if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
}
// images path
$radiotracer    = NUIMSA . 'webroot/img/radiotracer.jpeg';
$gammacamera    = NUIMSA . 'webroot/img/gammacamera.jpeg';
$alara          = NUIMSA . 'webroot/img/alara.jpeg';
$SNM            = NUIMSA . 'webroot/img/SNM.png';
$wb             = NUIMSA . 'webroot/img/wb.jpeg';
$cardiac        = NUIMSA . 'webroot/img/cardiac.jpeg';
$sirt           = NUIMSA . 'webroot/img/sirt.jpeg';
$petct          = NUIMSA . 'webroot/img/petct.jpeg';
$amiloid        = NUIMSA . 'webroot/img/amiloid.jpeg';

// get the script name could be 'banner.php or inicio.php'
$script = explode(DS, $_SERVER['SCRIPT_FILENAME']);
$script = strtolower($script[count($script) - 1]);
?>

<?php if ($script === 'banner.php'): ?>
    
<!-- muestro las páginas informativas completas ,... y los menús..? -->
<!-- Páginas de continuación y relleno -->
<div id="pag1" class="w3-container w3-light-grey w3-padding-xxlarge">
    <h1 class="w3-lato w3-jumbo w3-center">¿ Que es la Medicina Nuclear ?</h1>
    <div class="w3-container w3-theme-l4 w3-leftbar w3-theme-border">
        <p>La <strong>Medicina Nuclear</strong> es la especialidad médica que comprende la aplicación de muy pequeñas cantidades de sustancias radiactivas en el diagnóstico y tratamiento de las enfermedades y en su investigación.
        </p>
        <p>Las sustancias radiactivas usadas o <strong>radiofármacos</strong> están formados por un <i>fármaco</i> que actua de transportador hacia un órgano o sistema corporal específico y un <i>isótopo radiactivo</i> que  permite la obtención de <strong>imágenes</strong> o que ejerce, una vez depositado en el órgano en cuestión, un <b>efecto terapéutico</b>.
        </p>            
    </div>
    <div class="w3-row-padding">
        <div class="w3-col m4">
            <p>Los radiofármacos se introducen en el organismo humano por diversas vías, la más frecuente, es la intravenosa. La inyección se realiza, habitualmente, en la flexura o en el dorso de la mano y no ocasiona molestias, salvo, la de cualquier otra inyección en vena -similar a cuando nos hacemos una analítica convencional-.
            </p>
            <img class="w3-card-4 slideanim" src="<?= $radiotracer ?>" width="100%">
            <p>Una vez inyectado el paciente se convierte en la <i>fuente de radiación</i>. La radiación emitida o radiación <i>gamma</i> es similar a la que se produce en los tubos de rayos X y se recoge desde el exterior usando equipos denominados <strong><i>gammacámaras</i></strong> que la convierten, merced a procedimientos electrónicos y físicos, en imágenes del órgano sistema en estudio.
            </p>
            <p>Esto es, en algún modo, lo inverso de la radiología convencional en que la radiación se genera por fuentes externas como los tubos de rayos-X y la imagen se forma tras atravesar el organismo e incidir sobre un detector situado en el lado opuesto.
            </p>
        </div>

        <div class="w3-col m4">
            <img class="slideanim w3-card-4 w3-margin-top" src="<?= $gammacamera ?>" width="100%">
            <p>El uso de radiación en medicina se rige por el principio de usar dosis "tan bajas como sea razonablemente posible" o principio <b>ALARA</b> (de sus siglas en inglés <i>As Low As Reasonably Achievable</i>) teniendo como único límite conseguir imágenes de adecuada calidad diagnóstica.
            </p>
            <img class="w3-card-4 slideanim" src="<?= $alara ?>" width="100%">                        
            <p>Los radiofármacos diagnósticos se usan en dosis tan mínimas que carecen de efecto terapéutico y que, generalmente, no producen ningún efecto secundario o reacciones adversas.
            </p>
            <p>Las dosis de radiación recibidas en las pruebas de medicina nuclear son pequeñas, generalmente, de magnitud similar a las de las exploraciones radiológicas.
            </p>
        </div>
        
        <div class="w3-col m4">
            <p>Las exploraciones de medicina nuclear difieren de la radiología convencional en que el énfasis no está en la imagen de la anatomía sino en la función, por tal razón, es llamada <i>modalidad de imagen fisiológica</i>.
            </p>
            <p>Los estudios que hacemos son más órgano, sistema, tejido o enfermedad específicos  (ej.: gammagrafía pulmonar, cardiaca, ósea, cerebral, para infección, para el Parkinson, etc) que las imagenes de radiología convencional enfocadas a una sección particular del cuerpo (ej.: radioagrafía de tórax, de abdomen/pelvis, TAC craneal, etc.).
            </p>
            <p>Hoy en día disponemos de radiofármacos capaces de ofrecernos una imagen de nuestro cuerpo basada en la presencia o ausencia o en el aumento o disminución de ciertos receptores celulares o ciertas funciones moleculares como los estudios con MIBG (<i>Meta Iodo Bencil Guanidina</i>) con <sup>111</sup>Indio-Octreotido o la tomografía por emisión de positrones o PET (de sus siglas en inglés <i>Positron Emission Tomography</i>) de ahi la nueva denominación de la especialidad "<i>Medicina Nuclear e Imagen Molecular</i>".
            </p>
            <img class="w3-card-4 slideanim" src="<?= $SNM ?>" width="100%">                        
        </div>
    </div>
</div>

<div id="pag2" class="w3-container w3-foundation-light w3-padding-xxlarge">
    <h1 class="w3-lato w3-jumbo w3-center blanco w3-padding w3-text-shadow">Dos clases de Medicina Nuclear</h1>

    <div class="w3-row">
        <div class="w3-half w3-container w3-center">
            <p class="w3-lato w3-white-text w3-xxxlarge">Imagen nuclear clásica</p>
        </div>
            
        <div class="w3-half w3-container w3-center">
            <p class="w3-lato w3-white-text w3-xxxlarge">Imagen por PET</p>
        </div>
    </div>
    
    <div class="w3-row-padding">
        
        <div class="w3-col m6">
            <p>La medicina nuclear convencional o clásica utiliza isótopos emisores de radiación <i>gamma</i> y <i>gammacámaras</i> para la obtención de imágenes a partir de los fotones gamma que reciben.
            </p>
            <img class="slideanim w3-card-4" src="<?= $wb ?>" width="100%"/>
            <p>La medicina nuclear convencional cuenta con múltiples radiofármacos diseñados para estudiar la mayor parte de los sistemas orgánicos y tiene múltiples aplicaciones en el diagnóstico de padecimientos benignos y algunos malignos.
            </p>
            <img class="slideanim w3-card-4" src="<?= $cardiac ?>" width="100%" />
            <p>Por último, la medicina nuclear convencional tiene una vertiente terapéutica mediante el uso de isótopos emisores de radiación <i>beta</i> o <i>alfa</i> con aplicaciones clásicas como el  tratamiento de los padecimientos tiroideos -hipertiroidismo- y más novedosas como el tratamiento de las lesiones metastásicas hepáticas con microesferas de resina marcadas con Ytrio-90.
            </p>
            <img class="slideanim w3-card-4" src="<?= $sirt ?>" width="100%" />
        </div>

        <div class="w3-col m6">
            <p>Hace algunos años se incorporó a la medicina nuclear un nueva técnica de imagen denominada PET que hace uso de isótopos emisores de positrones (la <i>antipartícula</i> del electrón) y de equipos de imagen denominados <i>cámaras PET</i>.
            </p>
            <img class="slideanim w3-card-4" src="<?= $petct ?>" width="100%" />
            <p>Ofrece, frente a la medicina nuclear clásica, una mejor calidad de imagen y gracias a radiofármacos como la <i>fluorodesoxiglucosa marcada con Fluor-18</i> (F<sup>18</sup>-FDG) obtenemos imágenes muy específicas y nítidas de los procesos tumorales, generalmente, de forma muy precoz.</p>
            <img class="slideanim w3-card-4" src="webroot/img/pet.jpeg" width="100%" />
            <p>El PET cuenta, además de la F<sup>18</sup>FDG, con otros radiofármacos de gran utilidad y alta especificidad en el diagnóstico de ciertos tumores y se investiga muy activamente en el desarrollo de radiofármacos para el diagnóstico de ciertas demencias.
            </p> 
            <img class="slideanim w3-card-4" src="<?= $amiloid ?>" width="100%" />
        </div>        
    </div>
</div>
<?php endif ?>

<!-- FOOTER (PAGE SIX) -->
<footer>
    <div id="pag3" class="w3-container w3-foundation w3-padding-xxlarge">
        <div class="w3-container w3-Open-Sans-light">
            <h1 class="w3-center w3-lato blanco slideanim" style="padding-bottom: 20px">Otros recursos disponibles</h1>
            <div class="w3-row w3-center slideanim">
                <div class="w3-col m3 w3-foundation-border w3-padding-large">
                    <a href="#"><span><i class="w3-xxlarge fa fa-battery-3"  aria-hidden="true"></i></span></a><br/>
                    <h4 class="w3-lato blanco">Need more charge ?</h4>
                    <p>In et sapien leo. Phasellus a mi nisi. Integer suscipit aliquet eros, vitae scelerisque purus luctus quis. Ut turpis elit, efficitur et lectus vulputate, porttitor faucibus nunc.
                    </p>
                </div>
                <div class="w3-col m3  w3-foundation-border w3-padding-large">
                    <span><i class="w3-xxlarge fa fa-binoculars"  aria-hidden="true"></i></span><br/>                    
                    <h4 class="w3-lato blanco">Want to see more ?</h4>
                    <p>In et sapien leo. Phasellus a mi nisi. Integer suscipit aliquet eros, vitae scelerisque purus luctus quis. Ut turpis elit, efficitur et lectus vulputate, porttitor faucibus nunc.
                    </p>
                </div>
                <div class="w3-col m3  w3-foundation-border w3-padding-large">
                    <span><i class="w3-xxlarge fa fa-coffee"  aria-hidden="true"></i></span><br/>                    
                    <h4 class="w3-lato blanco">Want a cup a cofee ?</h4>
                    <p>In et sapien leo. Phasellus a mi nisi. Integer suscipit aliquet eros, vitae scelerisque purus luctus quis. Ut turpis elit, efficitur et lectus vulputate, porttitor faucibus nunc.
                    </p>
                </div>
                <div class="w3-col m3  w3-foundation-border w3-padding-large">
                    <span><i class="w3-xxlarge fa fa-info"  aria-hidden="true"></i></span><br/>                    
                    <h4 class="w3-lato blanco">Need more info ?</h4>
                    <p>In et sapien leo. Phasellus a mi nisi. Integer suscipit aliquet eros, vitae scelerisque purus luctus quis. Ut turpis elit, efficitur et lectus vulputate, porttitor faucibus nunc.
                    </p>
                </div>
            </div>
        </div>
        <div class="w3-center">
            <a class="w3-btn-floating-large w3-theme-l3 w3-text-theme" href="#pag1">&uarr;</a>
        </div>
    </div>
</footer>
