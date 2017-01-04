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
use function nuimsa\tools\convierteFecha;
use function nuimsa\tools\creaArray;
use function nuimsa\tools\initClases;
use function nuimsa\tools\buscaPacientes;
use function nuimsa\tools\creaDocGeneral;
use function nuimsa\tools\borraPaciente;
use function nuimsa\tools\clasesToXml;

require_once ROOT . DS . 'tools/tools.php';

// variables pasadas en el POST...
$varPost = count($_POST);

// para uso de las rutinas de borrado
// y busqueda
$ini = testInput($_POST["iniciales"]);
$fec = testInput($_POST["fecha"]);
// cambio la fecha a "cristiano"
$fec = convierteFecha($fec, 'local');
$exp = testInput($_POST["exploracion"]);
$xpath = "[iniciales='$ini' and fecha='$fec' and exploracion='$exp']";

// (1) Relleno las clases 
// a partir del $_POST 
$classArray = initClases($_POST, true);

// (2) Compruebo si está ya el paciente
$archivo = XML . 'datos_pacientes.xml';
$ruta = '/pacientes/paciente/demograficos' . $xpath;

$xmlDoc = new DOMDocument('1.0', 'utf-8');
$xmlDoc->load ($archivo);

$found = buscaPacientes($xmlDoc, $ruta);

if ($found->length == 0) {
    // no está sigo alegremente...
    clasesToXml($xmlDoc, $classArray);

    // si todo va bien contesto...
    $mensaje = "Los datos para \"" . $ini . "\" fueron guardados correctamente."; 
} else {
    // ya estaba en el archivo debo eliminar el registro y re-añadirlo
    borraPaciente($found);
    // ahora debo guardar la modificación
    clasesToXml($xmlDoc, $classArray);
    // si todo ha ido bien contesto...
    $mensaje = "Los datos para \"" . $ini . "\" fueron modificados correctamente.";
}
echo $mensaje;
