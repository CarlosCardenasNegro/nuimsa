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
 * Menu general de la app. Simple para usuarios normales
 * y más extenso para los profesionales.
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
 * Menú de la app
 */
 
// recopilo textos largos 
$profesionalesTXT   = "acceso a profesionales";
$inicioTXT          = "Vuelve a la página inicial";
$medicinaTXT        = "Información acerca de la especialidad";
$documentosTXT      = "Documentos informativos sobre los procedimientos y demás";
$consentimientosTXT = "Modelos de los consentimientos informados habituales";
$proyeccionesTXT    = "Proyecciones más usuales en función de la patología y hallazgos";
$garantiaTXT        = "Documentos relativos a la Garantía de la Calidad";
$entrevistaTXT      = "Entrevista clínica básica y listado de entrevistas previas";
$casosTXT           = "Casos de interés recogidos";
$copiaTXT           = "Gestión del archivo de pacientes";
$coloresTXT         = "Cambia el tema de la página";
$chatTXT            = "Inicia el simple chat";
$menuSmallTXT       = "Activa el menu de navegación";

$ul_class           = "w3-navbar w3-theme-d1 w3-left-align w3-tiny";
$li_class           = "w3-hide-medium w3-hide-large w3-opennav w3-right";
$a_class            = "w3-text-light-grey w3-hover-none w3-padding-large w3-hover-text-red";

// get the user
$user = $_SESSION['username'];
$auth = $user === 'Usuario invitado' ? false : true;
?>

<div  id="menubar" class="w3-top w3-card-4">
    <ul class="<?= $ul_class ?>">
        <!-- menu on small screens -->        
        <li class="<?= $li_class ?>">
            <a class="w3-padding-large" onclick="myFunction('navsmall')" title="<?= $menuSmallTXT ?>"><i class="fa fa-bars"></i></a>
        </li>

        <!-- menu inicio -->
        <?php if (!$auth): ?>    
            <li>
                <img src="<?= $esculapio_ico ?>" width="40px" style="position:relative; top: 4px; left: 5px"/>
            </li>
        <?php else: ?>
            <li class="w3-theme-d4">
                <a class="<?= $a_class ?>" onclick="cargaPag('inicio.php#inicio')" title ="<?= $inicioTXT; ?>">INICIO</a>
            </li>
        <?php endif ?>    
        <!-- standar menu -->
        <span class="w3-hide-small">
            <!-- Generalidades: Medicina Nuclear -->
            <li class="w3-dropdown-hover w3-hover-none w3-theme-d1">
                <a class="<?= $a_class ?>"  title="<?= $medicinaTXT; ?>">MEDICINA NUCLEAR&nbsp;<i class="fa fa-caret-down"></i></a>
                <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                    <a class="w3-hover-none" href="#pag1">Que es la medicina nuclear.?</a>
                    <a class="w3-hover-none" href="#pag2">Dos clases de medicina nuclear</a>
                </div>
            </li> 
            <!-- Generalidades: información solo en menu simple -->
            <?php if (!$auth): ?>    
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?>"  title="<?= $documentosTXT ?>">INFORMACION&nbsp;<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" onclick="cargaPag('consentimiento.php')" title="<?= $consentimientosTXT ?>">Consentimientos</a>
                        <a class="w3-hover-none" onclick="cargaPag('proyecciones.php')" title="<?= $proyeccionesTXT ?>">Proyecciones</a>
                        <a class="w3-hover-none" onclick="cargaInfo('cisterno.xml')" title="<?= $proyeccionesTXT ?>">Exploraciones</a>                    
                    </div>
                </li>        
            <?php else: ?>
                <!-- Menu: garantía de calidad -->
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?>"  title="<?= $garantiaTXT; ?>">GARANTIA DE CALIDAD&nbsp;<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" href="http://localhost/IRA/GC/Consentimientos/" target="_blank" title="<?= $consentimientosTXT; ?>">Consentimientos</a>
                        <a class="w3-hover-none" href="http://localhost/IRA/GC/Preparación/" target="_blank" title="<?= $proyeccionesTXT; ?>">Preparación del paciente</a>                
                        <div class="w3-dropright-hover">
                            <a class="w3-hover-none" style="padding: 0px !important">Procedimientos operativos&nbsp;<i class="fa fa-caret-right"></i></a>
                            <div class="w3-dropright-content w3-theme-d1 w3-card-4" style="opacity: .9">
                                <a class="w3-hover-none" href="http://localhost/IRA/GC/PO/PO Diagnósticos/" target="_blank">PO Diagnósticos</a>
                                <a class="w3-hover-none" href="http://localhost/IRA/GC/PO/PO Terapéuticos/" target="_blank">PO Terapéuticos</a>
                                <a class="w3-hover-none" href="http://localhost/IRA/GC/PO/PO Radiofarmacia/" target="_blank">PO Radiofarmacia</a>
                                <a class="w3-hover-none" href="http://localhost/IRA/GC/PO/PO Técnicas Específicas/" target="_blank">PO Técnicas especiales</a>
                            </div>
                        </div>
                        <div class="w3-dropright-hover">
                            <a class="w3-hover-none" style="padding: 0px !important">Instalación radiactiva&nbsp;<i class="fa fa-caret-right"></i></a>
                            <div class="w3-dropright-content w3-theme-d1 w3-card-4" style="opacity: .9">
                                <div class="w3-dropright-hover">
                                    <a class="w3-hover-none" style="padding: 0px !important">Documentos&nbsp;<i class="fa fa-caret-right"></i></a>
                                    <div class="w3-dropright-content w3-theme-d1 w3-card-4" style="opacity: .9">
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Certificaciones/" target="_blank">Certificaciones</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Circulares Informativas/">Circulares informativas</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Comunicados/">Comunicados</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Inspecciones/" target="_blank">Inspecciones</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Memoria anual/" target="_blank">Memoria anual</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Notificaciones/" target="_blank">Notificaciones</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Resoluciones/" target="_blank">Resoluciones</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Documentos/Solicitudes/" target="_blank">Solicitudes</a>
                                    </div>
                                </div>
                                <div class="w3-dropright-hover">
                                    <a class="w3-hover-none" style="padding: 0px !important">Legislación y normativas&nbsp;<i class="fa fa-caret-right"></i></a>
                                    <div class="w3-dropright-content w3-theme-d1 w3-card-4" style="opacity: .9">
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/BOE/" target="_blank">BOE</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/CEE/" target="_blank">CEE directivas</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/CSN/" target="_blank">CSN</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/Generales/" target="_blank">Generales</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/IAEA/" target="_blank">IAEA</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/Legislación y normativas/SEPR/" target="_blank">SEPR</a>
                                    </div>
                                </div>                                    
                                <a class="w3-hover-none" href="#">Licencias</a>
                                <div class="w3-dropright-hover">
                                    <a class="w3-hover-none" style="padding: 0px !important">Protección radiológica&nbsp;<i class="fa fa-caret-right"></i></a>
                                    <div class="w3-dropright-content w3-theme-d1 w3-card-4" style="opacity: .9">
                                        <a class="w3-hover-none" href="http://localhost/IRA/PR/Documentacion/" target="_blank">Documentacion</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/PR/Dosimetria/" target="_blank">Dosimetria</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/PR/Gestión de cámara caliente/" target="_blank">Gestión de cámara caliente</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/PR/PR Operacional/" target="_blank">PR Operacional</a>
                                        <a class="w3-hover-none" href="http://localhost/IRA/PR/Vigilancia ambiente de trabajo/" target="_blank">Vigilancia ambiente de trabajo</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Menú: Entrevista -->
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?>"  title="<?= $entrevistaTXT; ?>">ENTREVISTA<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" onclick="cargaPag('entrevista.php?id=nuevo')">Añadir caso</a>
                        <a class="w3-hover-none" onclick="listado()">Editar caso</a>
                        <a class="w3-hover-none" onclick="listado()">Borrar caso</a>
                    </div>
                </li>
                <!-- Menú: Almacén de casos -->      
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?>" title="<?= $casosTXT; ?>">ALMACEN DE CASOS<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <span id="casos"></span>
                    </div>
                </li>
                <!-- Menú: Estado -->
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?> "  title="<?= $copiaTXT ?>">ESTADO<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" onclick="infoArchivo ('info')">Info del archivo</a>
                        <a class="w3-hover-none" onclick="infoArchivo ('salva')">Guardar copia</a>
                        <a class="w3-hover-none" onclick="infoArchivo ('restaura')">Restaurar copia</a>     
                    </div>
                </li>
                <!-- Menú: News -->      
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?> "  title="<?= $copiaTXT ?>">NOTICIAS<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'JNM'}).done ( function (data) { $('#cuerpo').html(data); });">JNM News</a>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'EANM'}).done ( function (data) { $('#cuerpo').html(data); });">EANM News</a>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'CNM'}).done ( function (data) { $('#cuerpo').html(data); });">CNM News</a>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'IJNM'}).done ( function (data) { $('#cuerpo').html(data); });">Indian JNM</a>
                        <hr>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'YO'}).done ( function (data) { $('#cuerpo').html(data); });">Nuimsa</a>
                        <hr>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'GOOGLE'}).done ( function (data) { $('#cuerpo').html(data); });">Google News</a>
                        <a class="w3-hover-none" onclick="$.post(<?= "'" . NUIMSA . 'src/code/getrss.php' . "'" ?>, {'q' : 'NCB'}).done ( function (data) { $('#cuerpo').html(data); });">NBC News</a>
                    </div>
                </li>
                <!-- Menú: Temas -->      
                <li class="w3-dropdown-hover w3-hover-none">
                    <a class="<?= $a_class ?>" title="<?= $coloresTXT; ?>">TEMAS<i class="fa fa-caret-down"></i></a>
                    <div class="w3-dropdown-content w3-theme-d1 w3-card-4" style="opacity: .9">
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/red.css'}); setCookie('colores', 'red.css', 365);">Rojizo</a>
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/blue.css'}); setCookie('colores', 'blue.css', 365);">Azulado</a>
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/emerald.css'}); setCookie('colores', 'emerald.css', 365);">Esmeralda</a>
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/green.css'}); setCookie('colores', 'green.css', 365);">Verdoso</a>
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/bocacho.css'}); setCookie('colores', 'bocacho.css', 365);">Bocacho</a>          
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/turquesa.css'}); setCookie('colores', 'turquesa.css', 365);">Turquesa</a>          
                        <a class="w3-hover-none" onclick="$ ( '#color' ).attr({'href':'webroot/css/colores/awesome.css'}); setCookie('colores', 'awesome.css', 365);">Modern green</a>
                   </div>
                </li>
                <!-- Menú: Chat -->
                <li>
                    <a class="<?= $a_class ?>" onclick="chatting()" title ="<?= $chatTXT; ?>">CHAT</a>
                </li>
            <?php endif ?>    
            <!-- Menú: info -->    
            <li class="w3-right">
                <a href="#pag3" onclick="" class="w3-padding-large w3-hover-red"><i class="fa fa-question-circle-o"></i></a>
            </li>            
            <?php if (!$auth): ?>    
                <li class="w3-right">
                    <a class="w3-padding-large w3-hover-red"  onclick="accesoProfesionales()" title="<?= $profesionalesTXT ?>">PROFESIONALES</a>
                </li>
            <?php endif ?>
        </span>
    </ul>
</div>

<!-- Navbar on small screens -->
<div id="navsmall" class="w3-hide w3-hide-large w3-hide-medium w3-top w3-card-4">
  <ul class="w3-navbar w3-left-align w3-theme-d1 w3-tiny">
    <!-- <li><a class="w3-padding-large" onclick="cargaPag('inicio.html')">INICIO</a></li> -->
    <li><a class="w3-padding-large" onclick="cargaPag('consentimiento.php')">CONSENTIMIENTOS</a></li>
    <li><a class="w3-padding-large" onclick="cargaPag('proyecciones.php')">PROYECCIONES</a></li>

    <li class="w3-accordion w3-padding-large" onclick="myFunction('acc1')">ENTREVISTA&nbsp;<i class="fa fa-caret-down"></i>
        <div id="acc1" class="w3-accordion-content">
            <a class="w3-padding-large" onclick="cargaPag('entrevista.php?id=nuevo')">Paciente nuevo</a>
            <a class="w3-padding-large" onclick="cargaPag('entrevista.php?id=hoy')">Lista de pacientes</a>
        </div>
    </li>

    <li><a class="w3-padding-large" href="#" onclick="cargaPag('proyecciones.php')">CASOS...</a></li>

    <li class="w3-accordion w3-padding-large" onclick="myFunction('acc2')">PACIENTES&nbsp;<i class="fa fa-caret-down"></i>
        <div id="acc2" class="w3-accordion-content">
            <a class="w3-padding-large" onclick="$.post('copia.php', {'value': 'info'}).done (function(data) { $( '#dialogo' ).html(data); });">Info del archivo</a>
            <a class="w3-padding-large" onclick="$.post('copia.php', {'value': 'salva'}).done (function(data) { $( '#dialogo' ).html(data); });">Guarda copia</a>
            <a class="w3-padding-large" onclick="$.post('copia.php', {'value': 'restaura'}).done (function(data) { $( '#dialogo' ).html(data); });">Restaura copia</a>
        </div>
    </li>
        
    <li class="w3-accordion w3-padding-large" onclick="myFunction('acc3')">NOTICIAS&nbsp;<i class="fa fa-caret-down"></i>
        <div id="acc3" class="w3-accordion-content">
            <a class="w3-padding-large" onclick="showRSS('JNM')"><i class="fa fa-rss"></i>&nbsp;JNM</a>
            <a class="w3-padding-large" onclick="showRSS('EANM')"><i class="fa fa-rss"></i>&nbsp;EANM</a>
            <a class="w3-padding-large" onclick="showRSS('CNM')"><i class="fa fa-rss"></i>&nbsp;CNM</a>
            <a class="w3-padding-large" onclick="showRSS('INM')"><i class="fa fa-rss"></i>&nbsp;INM</a>
            <a class="w3-padding-large" onclick="showRSS('NUIMSA')"><i class="fa fa-rss"></i>&nbsp;NUIMSA</a>
            <a class="w3-padding-large" onclick="showRSS('GOOGLE')"><i class="fa fa-rss"></i>&nbsp;GOOGLE</a>
            <a class="w3-padding-large" onclick="showRSS('NCB')"><i class="fa fa-rss"></i>&nbsp;NCB</a>
        </div>
    </li>
    
    <li class="w3-accordion w3-padding-large" onclick="myFunction('acc4')">COLORES&nbsp;<i class="fa fa-caret-down"></i>
        <div id="acc4" class="w3-accordion-content">
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/red.css'});">Rojizo</a>
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/blue.css'});">Azulado</a>    
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/emerald.css'});">Esmeralda</a>
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/green.css'});">Verdoso</a>
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/bocacho.css'});">Bocacho</a>
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/turquesa.css'});">Turquesa</a>
            <a class="w3-padding-large" onclick="$ ( '#color' ).attr({'href':'css/colores/awesome.css'});">Modern green</a>
        </div>
    </li>
</ul>
</div>

<script>
$(function () {
    // crea dinamicamente 
    // el menu de Almacén de casos...
    $.post(APP + 'listado.php?value=categoria').done( function (data) { 
        var obj = JSON.parse(data);
        var salida = "";
        var $quiz = APP + 'quiz.php';

        // primero la imagen del día
        click = "$.post('" + $quiz + "',{'modo':'dia'}).done( function (data) { $( '#contenido' ).html(data); $('#learning, #carrousel' ).fadeTo('slow', 1); $imgActual = 1; })";
        salida += '<a ' + 'id="0" class="w3-hover-none" onclick="' + click + '"' + ' >';
        salida += 'Imagen del día</a>';
        salida += '<hr/>';
        
        // categorías
        var con = 1;
        for (var i = 0; i < obj.length; i++) {
            click = "listaCasos(this.id,'" + obj[i]['description'] + "')";
            salida += '<a ' + 'id="' + con.toString() + '" class="w3-hover-none" onclick="' + click + '"' + ' >';
            salida += obj[i]['description'] + '</a>';
            con += 1;
        }
        
        salida += '<hr/>';

        // todos los casos
        click = "listaCasos('7', 'Todos los casos')";
            
        salida += '<a ' + 'id="' + con.toString() + '" class="w3-hover-none" onclick="' + click + '"' + ' >';
        salida += 'Todos los casos</a>';

        // caso nuevo        
        salida += '<hr class="w3-theme"/>';
        click = "cargaPag('caso.php?modo=nuevo')";
        salida += '<a ' + 'id="' + con.toString() + '" class="w3-hover-none" onclick="' + click + '"' + ' >';
        salida += 'Añadir Caso nuevo' +  '</a>';
        
        $( '#casos' ).html(salida);
    }); 

    // muestro banner Acerca de...
    $( '#userbar a' ).show();    

    // gestión del menu
    $( '.w3-dropright-hover' ).hover( function() {
        
        // recupero posición absoluta de la opción de menu de origen <a>...
        // ojo el offset es con respecto a la página completa...
        var divTop = $(this).children( 'a' ).position().top;
        // situo el menu dropright 
        $(this).children( 'div').css('top', divTop).toggle();
    
        // evito burbujas,... no se si es necesario...
        event.defaultPrevented = true;
    });

    // desplazamiento suave de páginas
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
