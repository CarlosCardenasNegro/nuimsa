+<?php
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
 * Generic upload
 */
use function nuimsa\tools\testInput;
use function nuimsa\tools\convierteFecha;
use function nuimsa\tools\initClases;
use function nuimsa\tools\buscaPacientes;
use function nuimsa\tools\borraPaciente;
use function nuimsa\tools\clasesToXml;
use function nuimsa\tools\natural_lp;

/**
 * Load constants...
 */
 if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
}

require_once ROOT . DS . 'src/tools/tools.php';
 
$data = array();
$pass_get = false;
$pass_post = false;
$pass_files = false;

/**
 * Was does data passed..?
 */

if (isset($_GET) and count($_GET) > 0) {
    // GET..?
    $pass_get = count($_GET);
    foreach ($_GET as $key => $value) {
        $data[$key] = testInput($value);
    }
}
if (isset($_POST) and count($_POST) > 1) {
    // POST..?
    // Nota: siempre al menos pasaré el
    // nombre de la rutina a utilizar
    // en $data['rutina']
    $pass_post = count($_POST);
    foreach ($_POST as $key => $value) {
        $data[$key] = testInput($value);
    }
}
if (isset($_FILES) and count($_FILES) > 0) {
    // FILES..?
    $pass_files = count($_FILES);
}

if (!$pass_get and !$pass_post and !$pass_files) {
    // no data..¡
    $return = "Error<p class='w3-center'>No se han recibbido datos desde el cliente Web.</p>";
}

/**
 * Gestiono los datos en función
 * del origen ($data['rutina'])
 */
 switch ($data['rutina']) {
     
     case 'upload':
        // upload de un solo archivo
        // recuperado desde entrevista.php
        // mas adelante lo pasaré directamente
        $ini = $data['ini'];
        $fec = $data['fec'];
        $uploaddir = ROOT . DS . "scan/$fec";

        if (!file_exists($uploaddir)) {
            mkdir($uploaddir);
        }    
        $uploaddir .= "/$ini/"; 
        if (!file_exists($uploaddir)) {
                mkdir($uploaddir);
        }

        foreach($_FILES as $file) {
            $destino = $uploaddir . strtolower(basename($file['name']));
            if (move_uploaded_file($file['tmp_name'], $destino)) {
                $return = "Exito<p class='w3-center'>El archivo ($destino) fue subido con éxito.</p>";
            } else {
                $return = "Error<p class='w3-center'>Se ha producido un error al subir el archivo $destino.</p>";
            }
        }
        break;
        
     case 'caso':
         // caso de interes
         break;
     
     case 'procesado':
        /**
         * Antes de guardar el formulario
         * voy a analizar el texto contenido
         * en los campos tipo TEXTAREA.
         * Los posibles campos son 11:
         * - orden
         * - otras
         * - precipitado
         * - loc_anatomica
         * - loc_region
         * - desde_otra
         * - motivo_info
         * - clinica_info
         * - observaciones
         * - desde_otra_d
         * - desde_otra_i
         */
        $campos = array('orden', 'otras', 'precipitado', 'loc_anatomica',
            'loc_region', 'desde_otra', 'motivo_info', 'clinica_info',
            'observaciones', 'desde_otra_d', 'desde_otra_i');
        foreach ($campos as $value) {
            if (!empty($_POST[$value])) {
                $_POST[$value] = natural_lp(testInput($_POST[$value]));
            }
        } 
        
        // formulario de entrevista clínica básica
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
            // si todo ha ido bien contesto...
            $return = "Exito<p class='w3-center'>Los datos para \"" . $ini . "\" fueron guardados correctamente.</p>"; 
        } else {
            // ya estaba en el archivo debo eliminar el registro y re-añadirlo
            borraPaciente($found);
            // ahora debo guardar la modificación
            clasesToXml($xmlDoc, $classArray);
            // si todo ha ido bien contesto...
            $return = "Exito<p class='w3-center'>Los datos para \"" . $ini . "\" fueron modificados correctamente.</p>";
        }
        break;
}
echo $return;
        
