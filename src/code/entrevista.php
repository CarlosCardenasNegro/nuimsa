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

use function nuimsa\tools\testInput;
use function nuimsa\tools\initClases;
use function nuimsa\tools\creaSecciones;
use function nuimsa\tools\getDOMValores;
use function nuimsa\tools\muestraListado;
use function nuimsa\tools\convierteFecha;
use function nuimsa\tools\buscaPacientes;

require_once ROOT . DS . 'src/tools/tools.php';

/**
 * Entrevista.php - Formulario de entrevista al paciente.
 */ 
$title = "&nbsp;Entrevista clínica básica";
$salida = '';
$get = isset($_GET['id']) ? testInput($_GET['id']) : 'otro';
$archivo = XML . 'datos_pacientes.xml';
        
/**
 * los pacientes pueden pasarse como
 * como ini+fecha+exploracion para
 * para su recuperación individual
 */
switch ($get) {
    case 'nuevo':
        // inicializo clases
        $classArray = initClases();
        // las envio a pantalla
        // como viene de un $_POST le añado
        // el $flag
        creaSecciones($classArray);    
        break;

    case 'lista':
        // listado de pacientes...
        // OJO, paso un GET (id) y un POST (fecha)
        // la inicial no hay fecha
        $fecha = testInput($_POST["fecha"]);     
        // ojo la paso a cristiano
        $fecha = convierteFecha($fecha, 'local');        
        $path = "[fecha='$fecha']";
        $ruta = '/pacientes/paciente/demograficos' . $path;
        $xmlDoc = new \DOMDocument('1.0', 'utf-8');
        $xmlDoc->load ($archivo);
        
        $count = 0;    
        $res = buscaPacientes($xmlDoc, $ruta);    
        
    if ($res->length == 0) {        
        // no se encontraron pacientes
        echo 'Error';
        return;        
    } else {        
        // se encontraron 1 o más pacientes se muestra el listado de encontrados
        // y se llamará de nuevo a entrevista pero esta vez con todos los datos
        // de localización de un SOLO paciente.
        // La gestión de pulsar ESC y no seleciconar ningun paciente la hare 
        // en javascript (tal vez recuperando la tecla ESC..?)
        echo muestraListado($res, $fecha); // la función gestiona la selección... por si sola
        return;        
    }
    break;
     
   default:
        // se llega aquí desde la selección de la tabla listada antes
        // recupero todos los datos y sigo con toda la pesca...        
        $ini = $_GET["ini"];
        $fec = $_GET["fec"];
        $exp = $_GET["exp"];
        $path = "[iniciales='$ini' and fecha='$fec' and exploracion='$exp']";
        $ruta = '/pacientes/paciente/demograficos' . $path;
        $xmlDoc = new \DOMDocument('1.0', 'utf-8');
        $xmlDoc->load ($archivo);
        
        $paciente = buscaPacientes($xmlDoc, $ruta);    
        $valores = getDOMValores($paciente);
        
        // inicializo clases
        $classArray = initClases($valores);
        // las envio a pantalla
        // Demograficos, Solicitud, Acude_por, Clinica e Incidencias siempre visibles...        
        $tatus = array (
            true,
            true,
            true,
            $classArray[3]->estado(),
            $classArray[4]->estado(),
            $classArray[5]->estado(),
            $classArray[6]->estado(),
            true,
            true,
            $classArray[9]->estado(),
            $classArray[10]->estado(),
            $classArray[11]->estado(),
            $classArray[12]->estado(),
            $classArray[13]->estado()
        ); 
        creaSecciones($classArray, $tatus);    
        break;
} 
?>
<script>
// efecto al cargar la página (slideUp)
$( function() {
    //unbind events handlers,...no se porque,...pero..¡¡
    $( '#entrevista form > div header').unbind("click");
    
    // events handle's
    // headers (15), check's (22) and radio's (73)
    $( '#entrevista form > div header').on ("click", function() { headerClick ($(this)); });
    
    // textarea para pasarlos a minúsculas
    $( 'textarea' ).on ('blur', function() { $( this ).val(convierteMinusculas(this)); });    
        
    // (3) añado evento para la gestión de la carga de archivos/imágenes
    $( '#nom_arc' ).attr({'onchange':'prepareUpload(event)'});

    // animate
    $( ' #inicio' ).animate({top: '70px', opacity: '1'}, 400, function() { $(' #entrevista' ).fadeTo(400, 1); }); 

    // ini a mayusculas
    $( '#ini' ).attr({'onblur':'valida("ini")'});
    // foco inicial    
    $( '#ini' ).focus();
});
</script>
