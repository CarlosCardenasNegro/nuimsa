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
 * Main.js - Contiene las funciones de usuario.
 */

/**
 * Variables globales
 */
var $currentMark = 1;
var $imgActual = 1; 
var pagActual;

// variables globales para el Chat...
var chat;
var chatUser;
var chatActivo;
var temporizador;

// Paths para la app
/* Use the DS to separate the directories in other defines */
const DS = '/';
/* The full path to the directory which holds "src", WITHOUT a trailing DS. */
/**
 * OJO, cuando se ejecuta desde el debugger
 * location.pathname devuelve "/nuimsa/index.php" y
 * location.search devuelve "?XDEBUG_SESSION_START=netbeans-xdebug".
 * Por ello debemos de adelantarnos para evitar errores en el debugging
 */ 
const ROOT = (location.search).substr(0, 7) === "?XDEBUG" ? '/nuimsa/' : location.pathname;
/* The actual directory name for the application directory. Normally named 'src'. */
const APP_DIR = 'src/code';
/* Path to the application's directory. */
const APP = ROOT + APP_DIR + DS;

/**
 * carga de funciones iniciales y
 * asignación de eventos
 */
$( function() {
    // veo si hay un usuario ya autenticado,...
    if ($( '#userbar span' ).eq(1).html() === 'Usuario invitado') {
        // al inicio por defecto la página pública es banner
        pagActual = "#banner";     
        cargaPag('banner.php');
    } else {
        // ya estamos dentro
        pagActual = "#inicio";     
        cargaPag('inicio.php');
    }    
});

/**
 * Temporalmente usada para carga de casos
 */
function test(datos) {
    if( $( 'form#caso' ).data("submitted") === true) {
        event.preventDefault();
    } else {
        $( 'form#caso' ).data("submitted", true); 
        busy();
        $.ajax( {
            url: APP + 'cargaCaso.php',
            type: 'POST',
            data: datos,
            cache: false,
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.substr(0, 5) === "Error") {
                    displayMensaje('ggbb5566', 'Error!', '', result.substr(6, result.length));                
                } else {
                    displayMensaje('ggbb5566', 'Exito!', '', result);                
                }
            // volvemos a inicio
            cargaPag('inicio.php');
            }
        });
    }
}

/**
 * Gestiona el acceso a los profesionales
 */
function accesoProfesionales() {
    $.post(APP + 'autenticar.php').done ( function (data) {
        if (data.substr(0, 2) === 'ok') {
            $( '#userbar span' ).eq(1).html( data.substr(5, data.length));
            $( '#userbar a img').css({'display': 'block'});
            cargaPag("navbar.php");    
            cargaPag('inicio.php');
        } else {
            $mensaje = '<p class="w3-center">El nombre de usuario o la clave son incorrectos.</p>';
            displayMensaje('bv001', 'Error!', '', $mensaje, {'width':'50%'});
       }
    });
}
/**
 * Carga las páginas de la app mediante ajax
 *
 * @param str pag Nombre de la página a cargar
 * @return text/html
 */
function cargaPag(pag) {
    url = APP + pag;
    quiz = APP + 'quiz.php';
    $.ajax({
        url: url,
        context: document.body,
        success: function(responseText) {
            if(pag.substr(0, 6) === "navbar") {
                // carga el menu
                $( "#menuSlot" ).html(responseText);
            } else {
                // para test solo... caso.php
                // prefiero cargarlo como un diálogo
                if (pag === 'caso.php?modo=nuevo') {
                    // lo cargo como un dialog...
                    $( '#dialogo' ).html(responseText);
                    return;
                }                
                // carga pagina y ejecuta cualquier
                // script que se encuentre
                $( "#cuerpo" ).html(responseText);
                $( "#cuerpo" ).find("script").each(function(index) {
                    eval($(this).text());
                });
                // caso especial, inicio.php
                if (pag === 'inicio.php') {
                    // cargo la imagen del dia       
                    $.post(quiz, {'modo': 'dia'}).done( function (data) {
                        $( '#contenido' ).html(data);        
                        // muestro todo
                        $('#learning, #carrousel' ).fadeTo('slow', 1);
                    });
                }
            }
        }
    });
};
/**
 * Carga las páginas de información sobre exploraciones
 *
 * @param string $info Nombre del archivo xml de información
 */
function cargaInfo( filename ) {
    $.post(APP + 'readinfo.php', {'ci': filename}).done ( function (data) {
        if (data.substr(0, 5) === "Error") {    
            $mensaje = '<p class="w3-center">El archivo de información solicitado no existe o es incorrecto.</p>';
            displayMensaje('bv003', 'Error!', '', $mensaje, {'width':'70%'}, 'timer');
        } else {
            $( '#cuerpo' ).html(data);
        }
    });
}
/**
 * scripts para la entrevista clínica
 */
/**
 * Envía datos de los formularios:
 * Entrevista clínica
 */
function sendData() {   
    busy();
    var datos = new FormData($( 'form' )[0]);
    datos.append('rutina', 'procesado');
    
    url = APP + "upload.php";
    $.ajax({
        url: url,
        type: 'post',
        data: datos,
        cache: false,
        processData: false,
        contentType: false,
        success: function(responseText) {
            displayMensaje('up002', 'Exito!', '',responseText,'','timer');      
            cargaPag('inicio.php');
        }
    });
}
/**
 * Prepara el upload de los archivos a cargar en el servidor
 * 
 * @param {type} event
 * @returns {undefined}
 */
function prepareUpload(event) {    
    var files;    
    files = event.target.files;
    
    // Envio las iniciales y fecha para preparar los directorios
    // de destino
    var iniciales = $('#ini').val();
    var fecha = $('#fec').val();
    fecha =  fecha.replace('-', '');
    fecha =  fecha.replace('-', '');
    // oculto pantalla a modo de "busy"...
    busy(true);
    // los he de enviar en su propio ajax request...
    var data = new FormData();
    data.append('rutina', 'upload');
    data.append('ini', $('#ini').val());
    data.append('fec', $('#fec').val());
    
    $.each(files, function(key, value) { data.append(key, value); });
    $.ajax ({
        url: APP + 'upload.php',
        type: 'POST',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        success: function(responseText) {
            if (responseText.substr(0,5) === 'Exito') {
                displayMensaje('up001', 'Exito!', '',responseText, '', 'timer');
                $ini = responseText.indexOf('(') + 1;
                $end = responseText.indexOf(')');                
                $archivo = responseText.substring($ini, $end);
                // encadeno los archivos subidos...
                if (!$( '#nom_arc_hid' ).attr('value')) {
                    $arch = $archivo;
                } else {
                    $arch = $( '#nom_arc_hid' ).attr('value') + ';' + $archivo;
                }
                $( '#nom_arc_hid' ).attr({'value': $arch});
            } else {
                displayMensaje('up001', 'Error!', '',responseText, '', 'timer');
            }
        }
    });
}
/**
 * Crea un efecto de "ocupado" insertando un spinner
 */
function busy(status) {
    if (status) {
        var $temp = "<div class='popup'>";
        $temp += "<i class='w3-jumbo fa fa-spinner fa-pulse' aria-hidden='true' style='position:relative; top:50%;left:50%'>";
        $temp += "</i></div>";    
    } else {
        $temp ='';
    }  
    $( '#dialogo' ).html($temp);    
}
/**
 * ENTREVISTA CLINICA
 *
 * Gestores de eventos
 */
function headerClick( head ) {
    sec = head.next();    
    if (sec.css('display') == 'none') {
        // cerrado
        sec.slideDown('fast');
        head.css({'box-shadow': ''});
        // a primer plano
        var pos = head.parent().position().top + $( '#subentrevista' ).scrollTop();
        $( '#subentrevista').animate({scrollTop : pos}, '500');
    } else {
        // abierto
        sec.slideUp('fast');
        head.css({'box-shadow': 'rgba(0, 0, 0, 0.4) 2px 2px 4px 0px'});
    }
    event.cancelBubble = true;
}
function checkboxChange( check ) {
    var iden = event.target;
    switch (iden.parentNode.parentNode.id) {
        case 'solicitud':
            // caso de interes...
            var fecha = $( '#fec' ).val();            
            //salvaCaso();
            break;
            
        case 'acude_por':
            // el mas importante...
            var id = iden.id;
            xtem = true;
            // muestro los básicos
            switch(id) {
                case 'pol':
                case 'dol':
                case 'mol':
                case 'dif':
                    // conque uno este checked vale...
                    if (iden.checked) { 
                        xtem = true;
                    } else {
                        // solo si están los cuatro unchecked...
                        if (!$('#pol').prop('checked') &&
                            !$('#dol').prop('checked') &&
                            !$('#mol').prop('checked') &&
                            !$('#dif').prop('checked'))
                        { xtem = false; }                            
                    }
                    
                    $secciones = ['localiza','califica_como','evolucion','motivo','clinica', 'incidencias'];
                    
                    if (xtem) {

                        showHideSecciones($secciones, true, true);

                    } else {

                        showHideSecciones($secciones, false, true);
                        
                    }                    
                    break;

                case 'sen':
                    // SDRC
                    $secciones = ['localiza','califica_como','evolucion','motivo','clinica','SDRC', 'incidencias'];
                    
                    if (iden.checked) {

                        showHideSecciones($secciones, true, false);

                    } else {
                        
                        /**
                         * si he decidido anularlo debo avisar al usuario si hay ya algún dato entrado...
                         */ 
                        var men = "<p class='w3-center'>¿ Descarto los datos entrados en la sección relativa al <strong>SDRC</strong>  : ";
                        men += "<input id='10001-i' type='text' name='respuesta' value='' autofocus='true' maxlength='2' placeholder='si/no' style='width: 10%' />";
                        men += "</p>";
                        men += "<button class='w3-btn w3-wide w3-theme-d3 w3-hover-theme w3-hover-theme:hover' id='10001-b' style='width:100%' onclick='casoEspecial($( \"#10001-i\" ).val().toLowerCase(), $secciones )'>Aceptar</button>";
                        displayMensaje('dia002', 'Eliminar sección', '', men, {'icon': 'fa fa-question', 'padding': '2%'});

                    }
                    break;
                    
                case 'pro':
                    // prótesis
                    if (iden.checked) {

                        if ( $(' #loc_protesis' ).css('display')  === 'none' )   { $(' #loc_protesis' ).show(); }

                    } else {

                        // elimino todo rastro
                        $secciones = ['loc_protesis','lat_protesis','evolucion_protesis_der','evolucion_protesis_izq','clinica', 'motivo', 'incidencias'];
                        showHideSecciones($secciones, false, false);

                    }
                    break;
                case 'art':
                    // artrodesis
                    break;
                case 'fra':
                    // fractura
                    break;
                case 'onc':
                    //oncologico
                    if (iden.checked) {
                        
                        $secciones = ['clinica', 'incidencias'];
                        showHideSecciones($secciones, true, false);

                    } else {                    

                        $secciones = ['clinica', 'incidencias'];
                        showHideSecciones($secciones, false, false);
                        
                    }
                    break;
            }
    }
    event.preventDefault();  
}

function radioChange( radio ) {
    var iden = event.target.id;

    switch ( iden ) {
        // grupo solicitud
        case 'pre':
        case 'urg':
        case 'ing':
            
            // me envio un email
            var app = APP + 'mailme.php'
            var ini = $( '#ini').val();
            var fec = $( '#fec' ).val();
            var subject = "Paciente urgente, preferente o ingresado";
            var content = "Se ha realizado el " + fec + " al paciente '" + ini + "'. Es preferente, urgente o ingresado.\n";
            content += "El contenido de la solicitud es el siguiente: \n";
            content += $( '#ode' ).val() + '.\n';
            if ( $( ' #int' ).prop('checked') === true) {
                content += "(Nota: El caso se ha considerado de INTERES y para revisión).";
            }  
            $.post(app, {'subject': subject, 'content': content}).done ( function(resul) {
                if(resul === 'Exito!') {
                    displayMensaje('Men0909',resul,'','Se ha enviado un email de advertencia al médico.','', 'timer')
                } else {
                    displayMensaje('Men0910',resul,'','Ha habido un error al enviar el email.','', 'timer')                    
                }
            });
            break;
            
        // grupo de las prótesis... son radio buttons
        case 'hom':
        case 'cod':
        case 'man':
        case 'cad':
        case 'rod':
        case 'pie':
            $(' #lat_protesis' ).show();            
            break;
        // lateralidad de la prótesis
        case 'der':
            $(' #evolucion_protesis_der' ).show();
            $(' #clinica' ).show();
            $(' #motivo' ).show();
            $(' #incidencias' ).show();
            break;
        case 'izq':
            $(' #evolucion_protesis_izq' ).show();
            $(' #clinica' ).show();
            $(' #motivo' ).show();
            $(' #incidencias' ).show();
            break;
        case 'amb':
            $(' #evolucion_protesis_der' ).show();
            $(' #evolucion_protesis_izq' ).show();
            $(' #clinica' ).show();
            $(' #motivo' ).show();
            $(' #incidencias' ).show();
            break;            
    }
}

/**
 * Muestra y oculta las secciones pasadas
 * en el array $secciones según la variable
 * $estado (show=true o hide=false) con o sin test (true/false)
 * (oculta SOLO si están visibles y viceversa)
 *
 */
function showHideSecciones( $secciones, $estado, $test ) {

    for (var i = 0; i < $secciones.length; i++) {
        if ($estado) {

            // show
            if ($test) {
                if ( $( '#' + $secciones[i] ).css('display') === 'none' )       { $( '#' + $secciones[i] ).show(); }
            } else {
                $( '#' + $secciones[i] ).show();
            }
            
        } else {

            // hide
            if ($test) {
                if ( $( '#' + $secciones[i] ).css('display') === 'block' )       { $( '#' + $secciones[i] ).hide(); }
            } else {
                $( '#' + $secciones[i] ).hide();
            }
            
        }
    }
    
}

/**
 * Vacía el contenido de la sección correspondiente
 */
function swipeSecciones ( $secciones ) {
    for (var i = 0; i < $secciones.length; i++) {
        // tipo de campo
        // input's
        $campos = $( '#' + $secciones[i] + ' section input');
        for (var f = 0; f < $campos.length; f++) {
            $campos[f].value = "";
            if($campos[f].type === "radio" || $campos[f].type === 'checkbox') {
                $campos[f].value = "";
                $campos[f].checked = false;            
            }            
        }
        // textarea
        $campos = $( '#' + $secciones[i] + ' section textarea');
        for (var f = 0; f < $campos.length; f++) {
            $campos[f].value = "";
        }        
    } 
}

function casoEspecial( resul, $secciones ) {
    
    if (resul.toLowerCase() === 'si') 
    { 
        swipeSecciones($secciones);
    } 
    $( '#dia002' ).fadeTo(200,0).hide();
    showHideSecciones($secciones, false, false);                         
}

/**
 * listado
 *
 * listado de entrevistas de pacientes
 * ojo selecciono la fecha en el browser
 * recupero los datos desde php pasando
 * la fecha.
 */
function listado() {
    // lo de la fecha tiene delito... es que...
    var fecha = new Date();
    var ano = fecha.getFullYear();
    var mes = fecha.getMonth() + 1;
    if (mes < 10) { mes = '0' + mes.toString(); }
    var dia = fecha.getDate();
    if (dia < 10) { dia = '0' + dia.toString(); }
    var total = ano + '-' + mes + '-' + dia; 
    
    var fun = "";
    // creo el diálogo 
    var men = '<p class="w3-center">';
    men += "<label class='w3-xlarge'>Selecciona una fecha para el listado :&nbsp;&nbsp;&nbsp;</label>";
    men += "<input id='10234-i' type='date' name='fecha' value='";
    men += total + "' autofocus='true'/></p>";
    men += "<br/>";
    men += "<button class='w3-btn w3-wide w3-theme-d3 w3-hover-theme w3-hover-theme:hover' id='10234-b' style='width:100%' onclick='muestraListado($( \"#10234-i\" ).val())'>Aceptar</button>";

    displayMensaje('dia002', 'Listado de pacientes por fecha', '', men, {'icon': 'fa fa-question', 'padding': '2%'});
}

/**
 * Complementaria de la anterior para evitar
 * una función anónima tan gorda
 *
 * @param string fecha Fecha seleccionada
 */
function muestraListado( valor ) {
    // primero cierro el diálogo
    $( '#dia002' ).fadeTo('slow', 0).hide();
    $( '#dialogo' ).html('');
    // ahora llamo la función real
    $.post(APP + "entrevista.php?id=lista", {fecha: valor}, function (data) {
        if ( data === 'Error') {
            displayMensaje('d45364', 'Atención!', '', '<p class="w3-center">Lo siento, no se han realizado entrevistas en el día seleccionado.</p>', '', 'timer');
            cargaPag('inicio.php');
        } else {
            var d = new Date(valor);
            displayMensaje('d4504', 'Listado de estudios realizados el dia ' + d.toLocaleDateString(), '', data, {'width':'75%'});                        
        }
    });
}

/**
 * setCookie
 *
 * Guarda el color predeterminado en un cookie
 *
 * @param string cname
 * @param string cvalue
 * @param int exdays
 * @param string $color
 */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

/**
 * getCookie
 *
 * Recupera el color predeterminado desde un cookie
 *
 * @param string cname
 */
 function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

/**
 * Muestra un mensaje de alerta
 * 
 * Los opciones de los parámetros pasados son:
 * (1) si el $tipo no es estandar se usará como texto del header
 * (2) si añadimos una línea de código ésta se sumará al evento onclick.
 * OJO, debe incluir el ';' inicial¡¡.
 * (3) las opciones posibles recuperadas de $param[0] son:
 * - afectando a la apariencia general del diálogo (w3-modal-content):
 *      a) $width = '60%' 
 *      b) $height = '40%'
 *      b) $padding = '10%' 
 *      c) $icon = "" icono (fa fa...) a usar o nada 
 *      d) $color = "w3-theme-d4" (background-color del w3-modal-content que será el del header¡¡)
 *      e) $gclass = '' (clases generales que se le añaden)
 *
 * - afectando al header:
 *      b) $hclass = '' (clases a aplicar al header. Se añaden a las bases: w3-container w3-center w3-xlarge w3-padding)
 *
 * - afectando a la section:
 *      a) $scolor = "w3-white" (background-color de la section)
 *      b) $sclass = '' (clases a aplicar a la section. Se añaden a las bases: w3-container w3-large)
 *
 * Añado una opción nueva (sería el 6º parámetro) "timer" en el que se establecerá un timer para que la ventana se cierre
 * por si sola después de transcurridos 5sg. 
 * 
 * @param string $id la #id del DIV conteniendo este cuadro de dialogo (para su cierre)
 * @param string $tipo el tipo de mensaje (ver arriba)
 * @param string $accion la función o acción a realizar además de la básica (ver arriba)
 * @param string $mensaje el mensaje que queremos mostrar (html formateado si quieremos)
 * @param array $options diversas opciones posibles (ver arriba)
 */
function displayMensaje($id, $tipo, $accion, $mensaje, ...$param) {
    
    var $fun = '$( "#' + $id + '" ).fadeTo("slow", 0).hide()' + $accion;
    var $dialogo = '';

    // lo vacío de mensajes previos...
    $( '#dialogo' ).html(''); 
    
    // default values (w3-modal-content)
    $width = '70%';
    $height = '';
    $padding = '3%';
    $icon = '';        
    $color = "w3-theme-d2";
    $gclass = '';

    // header
    $hclass = '';

    // section
    $scolor = "w3-theme-l5";
    $sclass = '';
    
    // icon margins
    $iconmargin = '';
            
    switch ($tipo) {
        case "Exito":
            $icon = "fa fa-check-circle";
            $color = "w3-exito";
            break;
        case "Info":
            $icon = "fa fa-info-circle";
            $color = "w3-info";
            break;
        case "Atención":
            $icon = "fa fa-child";
            $color = "w3-atencion";
            break;
        case "Error":
            $icon = "fa fa-exclamation-triangle";
            $color = "w3-error";
            break;
    }

    // optional values
    if (arguments.length >= 5 && $param[0].length != 0) {
        $options = $param[0];

        for (prop in $options) {
            switch (prop) {
                case 'width':
                    $width = $options[prop];
                    break;
                case 'height':
                    $height = $options[prop];
                    break;
                case 'padding':
                    $padding = $options[prop];
                    break;
                case 'icon':
                    $icon = $options[prop];
                    break;
                case 'color':
                    $color = $options[prop];                    
                    break;
                case 'hclass':
                    $hclass = $options[prop];                    
                    break;                    
                case 'scolor':
                    $scolor = $options[prop];                    
                    break;
                case 'sclass':
                    $sclass = $options[prop];                    
                    break;
                case 'gclass':
                    $gclass = $options[prop];                    
                    break;                    
            }
        }    
    }
    
    // timer..?
    if (arguments.length === 6) {
        var generalTimer = setInterval(function() { $( '#' + $id ).fadeTo('slow', 0).hide();clearInterval(generalTimer); }, 2000);
    }
        
    // establezco el color del icono
    var $icolor = ($color.substr(0, 8) === 'w3-theme' ? 'w3-text-theme' : 'w3-text-' + $color.substr(3, $color.length));
    // ajusto el padding finalmente
    // padding bottom (respeto el 0% para la imagen de Acerca de...)
    $paddingbottom = '0%';
    if ($padding != '0%') {
        $padVal = Number($padding.substr(0, $padding.length - 1)) - 2;
        $paddingbottom = ( $padVal <= 2 ? '2%' : ($padVal.toString() + '%'));
    }
    
    // codigo html que crea la ventana
    dialogo = "<div id='" + $id + "' class='w3-modal' style='display: block'>";

    dialogo += "<div class='w3-modal-content w3-card-4 w3-round " + $color + " " + $gclass + "'";
    dialogo += " style='width: " + $width + "; height: " + $height + "'>";

    dialogo += "<header class='w3-container w3-center w3-xlarge w3-padding " + $hclass + "'>";
    dialogo += "<span onclick='" + $fun + "' class='w3-closebtn'>&times;</span>";
    dialogo += $tipo + "</header>" ;
    
    dialogo += "<section class='w3-container w3-large " +  $scolor + " " + $sclass + "' style='position: relative; height: 100%; border-radius: 0px 0px 5px 5px;";
    dialogo += " padding: " + $padding + "; padding-bottom: " + $paddingbottom + "'>";

    // hay icono ..?
    if ($icon !== '') {
        dialogo += "<i class='w3-xxxlarge w3-left " + $icolor + " " + $icon + "' style='padding-right: 2%; margin-top:2%'></i>";    
    }    
    dialogo += $mensaje;
    dialogo += "</section></div></div>";
    
    // lo muestro ...
    $( '#dialogo' ).html(dialogo); 
}


/**
 * Scripts para activar el menu en pantallas pequeñas
 * al pulsar el botón
 */
function myFunction(id) {
    
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") === -1) {
        x.className += " w3-show";
        
        if (id === 'navsmall') {
            $('#' + id).animate({'width': '60%'}, 400);
            $('#menubar ul li:eq(0) a i').attr('class', 'fa fa-times-circle');        
        }
        /*
        if (id.substr(0, 3) === 'acc') {
            // accordion
            //$('#' + id).animate({'height': '60%'}, 200);
        }
        */
    } else {
        if (id === 'navsmall') {
            $('#' + id).animate({'width': '0%'}, 400);
            $('#menubar ul li:eq(0) a i').attr('class', 'fa fa-bars');
        }
        /*
        if (id.substr(0, 3) === 'acc') {
            // accordion
            //$('#' + id).animate({'height': '0%'}, 200);
        }
        */
        x.className = x.className.replace(" w3-show", "");
        $('#menubar ul li:eq(0) a i').attr('class', 'fa fa-bars');
    }
}

/**
 * Recupera el SAPID para la exploración seleccionada
 */
function getSAPID( explo ) {
    $.post(ROOT + 'tools/tools.php', {'name': 'sapID', 'explo': explo}).done( function (data) {
       $( '#cod_SAP' ).val(data); 
    });

/*
    $.post('sapID.php', {'explo': explo}).done( function (data) {
       $( '#cod_SAP' ).val(data); 
    });
*/
} 

/**
 * Validación de los campos de la seccion 1 (demográficos)
 *
 * @param evento tipo blur
 */ 
function valida(campo) {

    var $id =  '#' + campo; 
    var value = $( $id ).val(); 
        
    if ( value == "" || value == null || value == "none") {
        // rutina de validación ante error...
        //displayMensaje('v0102', 'Atencion!','', '<p class="w3-center">El campo ' + $id + ' no puede quedar vacío.</p>','', 'timer');
    } else {
        if(campo = 'ini') { $( $id ).val(value.toUpperCase()); }
    } 
}

/**
 * Convierte el campo pasado a minúsculas. Respeta los puntos y seguido.
 *
 * Añado alguna cosilla para evitar convertir a minúsculas sigls y demás acrónimos
 *
 * @param string campo campo a modificar
 * @return string campo modificado
 */
function convierteMinusculas(campo) {
    $value = campo.value;
    
    if ($value.length <= 1) { return $value; }
    $res = '';
    $cadenas = $value.split('.');
    
    switch ($cadenas.length) {
        case 0: 
            // no había punto de separación
            // la paso a minúsculas y le añado
            // un punto al final
            $inicial = $value.substr(0, 1).toUpperCase();
            $resto = $value.substr(1, $value.length - 1).toLowerCase();
            $res += $inicial + $resto + '.';
            break;
        case 1:
            // hay al menos un punto final igual
            $inicial = $value.substr(0, 1).toUpperCase();
            $resto = $value.substr(1, $value.length - 1).toLowerCase();
            $res += $inicial + $resto + '.';
            break;
        default:
            // hay dos o más puntos (ojo un punto solo genera una cadena vacía, ej. puntos suspensivos ...)
            for (var i = 0; i < $cadenas.length; i++) {
                if ($cadenas[i].length > 0) {
                    $cadenas[i] = $cadenas[i].trim();
                    $inicial = $cadenas[i].substr(0, 1).toUpperCase();
                    $resto = $cadenas[i].substr(1, $value.length - 1).toLowerCase();
                    $res += $inicial + $resto + '. ';
                }
            }
    }
    return $res.trim(); 
}

/**
 * Gestión del carrousel
 *
 */
function goBackward() { 
    
    var $numImg = $( '#div-img > img' ).length + $( '#div-img > video' ).length;    

    if ($imgActual > 1 && $imgActual <= $numImg) {
        // retrocedo
        $act = '#img' + $imgActual.toString();
        $pre = '#img' + ($imgActual - 1).toString();
        $m_act = "#marks" + $imgActual.toString();
        $m_pre = "#marks" + ($imgActual - 1).toString();
        
        // img & h2 actuales a 0 y y mark a blanco
        $( $act ).fadeTo('slow', 0);
        $('#div-img > h2').eq($imgActual - 1).fadeTo('slow', 0);        
        $( $m_act ).css({'background-color' : 'white'});
        
        // img & h2 previas a 1 mark actual a gris        
        $( $pre ).fadeTo('slow', 1);        
        $('#div-img > h2').eq($imgActual - 2).fadeTo('slow', 1);        
        $( $m_pre ).css({'background-color' : 'red'});
        
        $imgActual -= 1;                    
    }
    //if (event.stopPropagation) { event.stopPropagation = true; }    
}
    
function goForward() {
    
    var $numImg = $( '#div-img > img' ).length + $( '#div-img > video' ).length;    

    if ($imgActual >= 1 && $imgActual < $numImg) {
        //avanzo
        $act = '#img' + $imgActual.toString();
        $sig = '#img' + ($imgActual + 1).toString();
        $m_act = "#marks" + $imgActual.toString();
        $m_sig = "#marks" + ($imgActual + 1).toString();
        
        $( $act ).fadeTo('slow', 0);
        $( $m_act ).css({'background-color' : 'white'});
        $('#div-img > h2').eq($imgActual - 1).fadeTo('slow', 0);                

        $( $sig ).fadeTo('slow', 1);        
        $( $m_sig ).css({'background-color' : 'red'});
        $('#div-img > h2').eq($imgActual).fadeTo('slow', 1);                
        
        $imgActual += 1;
    }        
}

function markClick( mrk ) {
    if (mrk != $imgActual) {
        $act = '#img' + $imgActual.toString();
        $sig = '#img' + mrk.toString();
        $m_act = "#marks" + $imgActual.toString();
        $m_sig = "#marks" + mrk.toString();
        
        $( $act ).fadeTo('slow', 0);
        $( $m_act ).css({'background-color' : 'white'});
        $('#div-img > h2').eq($imgActual - 1).fadeTo('slow', 0);                
        
        $( $sig ).fadeTo('slow', 1);        
        $( $m_sig ).css({'background-color' : 'red'});
        $('#div-img > h2').eq(mrk-1).fadeTo('slow', 1);

        $imgActual = mrk;
    }
}

/**
 * Lista los casos de acuerdo a la categoría
 *
 * @param int categoria Categoría a recuperar
 */
function listaCasos(categoria, descripcion) {
  
    $.post(APP + 'listado.php', {'seleccion': categoria, 'descripcion': descripcion}).done( function (data) {
        if (data === 'Error!') {
            $mensaje = "<p class='w3-center'>Se ha producido un error al cargar los datos.</p>";
            displayMensaje('xx091', 'Error!', '', $mensaje,'', 'timer');             
        } else {
            var options = { 'width':'90%',
                            'height': '90%',
                            'sclass': 'w3-overflow',
                            'padding':'1%'};
            displayMensaje('xx092', 'Selección de casos', '', data, options);                         
            //$( '#contenido' ).html(data);
        }        
    });
}
/**
 * Gestión de casos:
 * (1) Display
 * (2) Edición
 * (3) Borrado
 *
 * @param int num Es la id del paciente a mostrar
 */
function showCaso( event ) {
    
    // por defecto asumo display
    var $modo = 'display';
    var quiz = APP + 'quiz.php';
    var caso = APP + 'caso.php';
    // sobre quien pulsé..?
    var $key = event.target.nodeName;
    
    if ($key === "IMG" || $key === "BUTTON") {

        // pulsé sobre el icono... o sobre un botón
        var casoID = event.target.parentElement.parentElement.firstElementChild.innerText;        
        
        if ($key === "BUTTON") {
            $modo = (event.target.innerHTML).toLowerCase();
        }
        
    } else {
        
        // pulsé sobre una celda (menudo rollo recuperar el valor...¡¡)
        var casoID = event.target.parentElement.childNodes.item(0).childNodes.item(0).nodeValue;    
    }

    if ($modo === 'editar') {

        // paso el testigo a caso.php y recibo el caso a editar
        $.post(caso, {'casoID': casoID, 'modo': $modo}).done( function(data) {
            if (data === 'Error') {
                $mensaje = "<p class='w3-center'>Se ha producido un error al cargar los datos.</p>";
                displayMensaje('xx093', 'Error!', '', $mensaje, '', 'timer');             
            } else {
                // elimino listado
                $( '#xx093' ).fadeTo('slow', 0).hide();
                $( '#dialogo' ).html(''); 
                // muestro el nuevo contenido
                $( '#contenido' ).html(data);
            }
        });
            
    } else {
        
        // llamo a quiz.php
        $.post(quiz, {'casoID': casoID, 'modo': $modo}).done( function (data) {
            if (data === 'Error') {
                $mensaje = "<p class='w3-center'>Se ha producido un error al cargar los datos.</p>";
                displayMensaje('xx093', 'Error!', '', $mensaje, '', 'timer');             
            } else {
                // elimino listado
                $( '#xx093' ).fadeTo('slow', 0).hide();
                $( '#dialogo' ).html(''); 
    
                if (data === "Salir") {
                    // caso de borrado o edición
                    //cargaPag('inicio.php');
                } else {
                    // muestro el nuevo contenido
                    $( '#contenido' ).html(data);
                    $('#learning, #carrousel' ).fadeTo('slow', 1);
                    $imgActual = 1;        
                }
            }        
        });
    }    
}

/**
 * Muestra la información sobre el archivo datos_pacientes.xml y
 * Copia o Restaura el archivo
 *
 * OJO, la linea que había antes en el navbar era:
 *  ($.post('copia.php', {'value': 'info'}).done (function(data) { $( '#dialogo' ).html(data); });)
 *
 * @param string modo (info/copia/restaura)
 */
function infoArchivo (modo) {

    switch (modo) {
        case 'info':
            $head = 'Información del archivo de pacientes';
            break;
        case 'salva':
            $head = 'Salvando el archivo de pacientes';
            break;
        case 'restaura':
            $head = 'Restaurando la última copia del archivo de pacientes';
            break;        
    }    
    
    $.post(APP + 'copia.php', {'value': modo}).done (function(data) {         
        if (data.substr(0, 6) === 'Error!') {
            displayMensaje('xx098', 'Error!', '', data.substr(6, data.length));
        } else {            
            displayMensaje('xx098', $head, '', data, {'spamClass':'w3-large', 'width':'75%', 'icon': 'fa fa-database'});
        }
    });
}

/**
 * gestión del chat
 *
 */
function chatting() {
    var salida = "";
    chat =  new Chat();
    // desactivo temporizador
    clearInterval(chatActivo);
    // inicio chat al dia de hoy
    $.post ('process.php', {'function': 'getInitialState', 'file': file}).done ( function(data) {
        data = JSON.parse(data);
        state = data.state;
    });
    // creo ventana de chat y lo echo a andar
    salida = chat.createWindow();
    displayMensaje('chat001', 'Chat room', '; chat.destroy()', salida, {'width': '80%', 'height': '80%'});
    temporizador = setInterval(chat.update, 1000);   
    // y lo anuncio (asumo que existe chatSound...¡¡)
    document.getElementById( 'chatSound' ).play();
}

function chatStatus() {
    var usuario;
    // it's chat running..?
    if (chat == null) {
        // test estado del archivo
        getStateOfChat();
        if (chatInitialState != state) {
            // hay entradas nuevas
            if ($('#userbar_men').html() === '') {
                $( '#userbar_men').html('Se ha iniciado una sesión de chat...');                
                // lo anuncio (asumo que existe chatSound...¡¡)
                document.getElementById( 'chatInit' ).play();
                displayMensaje('chat090909','Atención!','','Un usario ha iniciado una sesión de chat.', {'icon':'fa fa-comments'}, 'timer');    
            }
        } else {
            // limpia el banner
            $( '#userbar_men').html('');                    
        }            
    } else {
        $( '#userbar_men').html('');
    }
}

function getInitialStateOfChat() {
    //establece el estado inicial
    $.post ('process.php', {'function': 'getState', 'file': file}).done ( function(data) {
        data = JSON.parse(data);
        state = data.state;
        chatInitialState = state;
    });
    chatUser = $( '#userbar span' ).eq(1).html();
    res = chatUser.split(' ');
    chatUser = res[0];
    // set temporizador inicial
    chatActivo = setInterval(chatStatus, 15000);    
}
/**
 * Muestra el diálogo Acerca de... clásico
 *
 */
function acercaDe() {   
    var $info;
    var $acercaDe = '';
    
    $info = "Estas páginas son el fruto del esfuerzo por amaestrar las técnicas web ";
    $info += "y aplicarlas al trabajo diario con el fin de mejorar su calidad y, ";
    $info += "de esta forma ofrecer una mejor atención a nuestros pacientes.";
    $info += "<br/><br/>";
    $info += "Los datos clínicos aqui reflejados carecen de filiación y, por tanto, solo nos son de ";
    $info += "utilidad a nosotros y no están relacionados con ningún paciente reconocible, ";
    $info += "sin embargo, son de una gran ayuda en nuestra labor diaria.";
    $info += "<br/><br/>";
    $info += "Los casos clínicos presentados en la página de inicio y a través del menú CASOS... ";
    $info += "pueden ser de utilidad para todo aquel interesado en la Medicina Nuclear. ";
    $info += "Iremos recogiendo progresivamente los casos de interés didáctico, anecdótico o científico ";
    $info += "que surgen en el día a día. Si son de vuestro interés nos damos por satisfechos.";
    $info += "<br/><br/>";
    $info += "La confección de todo esto no hubiera sido posible sin la intermediación de un accidente ";
    $info += "fortuito que tuvo al autor, <i>yo</i>, varios meses en el \"dique seco\" y, a pesar de los momentos malos ";
    $info += "-que los hubo y muchos- le regaló un montón de tiempo libre para aprovecharlo en dar rienda ";
    $info += "suelta a su profunda afición por la programación, la gestión y la docencia.";
    $info += "<br/><br/>";
    $info += "Quiero expresar mi agradecimiento a mi colega Enrique Molina quien me ha inspirado muchas cosas ";
    $info += "interesantes que han ido mejorarando la calidad del sitio web y a él mismo y a David Bermúdez por la ilusión con que ha esperado ";
    $info += "las sucesivas versiones hasta verlo hecho un producto \"visible\" y animándome a seguir.";    
    $info += "<br/><br/>";
    $info += "Por último, lo más importante, es el agradecimiento a mi esposa y a los \"niños\" que han aguantado ";
    $info += "mi mal humor cuando las cosas de salud no iban del todo bien y el innumerable número de horas ";
    $info += "que me he pasado castigándome la espalda y la vista delante de la pantalla de mi <i>iMac</i> leyendo ";
    $info += "incansablemente hasta aprender todo lo necesario para crear esto, mientras, ";
    $info += "como \"<i>efecto colateral</i>\" los he tenido abandonados,... un poco,... bastante,.. <b>demasiado</b>,...";
    $info += "<br/><br/>";
    $info += "Gracias por todo y a todos.";
    $info += "<br/><br/>";
    $info += "En San Miguel de Abona - Tenerife - España<br/>";
    $info += "8 de Febrero - Diciembre de 2016";
    
    $acercaDe += "<img src='./recursos/img/sanmiguel_1.jpeg' width='100%' height='100%' style='border-radius: 0px 0px 4px 4px'/>";
    $acercaDe += "<div id='acercade-txt'>";
    $acercaDe += "<p class='w3-center w3-text-white'>NM Toolkit</p>";
    $acercaDe += "<div>";
    $acercaDe += "<span id='acercade' class='w3-left w3-text-white'>" + $info + "</span>";    
    $acercaDe += "</div></div>";
    
    $accion = '; document.getElementById( "nature" ).pause()';
    displayMensaje('ad0202', 'Acerca de...', $accion, $acercaDe, {'width': '50%', 'padding': '0%', 'sclass': 'w3-lato','gclass':'acercade'});
    document.getElementById( 'nature' ).play();
    
}

