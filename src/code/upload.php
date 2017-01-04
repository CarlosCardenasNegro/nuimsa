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

$data = array();
$ini = strtolower(htmlspecialchars($_GET['ini']));
$fec = strtolower(htmlspecialchars($_GET['fec']));

if (isset($_GET['files'])) {

    $files = array();
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
            $data = "Exito<p class='w3-center'>El archivo ($destino) fue subido con éxito.</p>";
        } else {
            $data = "Error<p class='w3-center'>Se ha producido un error al subir el archivo $destino.</p>";
        }
    }
}
echo $data;