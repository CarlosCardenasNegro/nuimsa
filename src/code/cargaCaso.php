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

use Gregwar\Image\Image;
use function nuimsa\tools\testInput;
 
require_once ROOT . DS . 'src/tools/tools.php';

/**
 * Procesa los datos par un caso Quiz nuevo:
 */
$iniciales = strtoupper(testInput($_POST['iniciales']));
$resul = array(
    'categoria' => testInput($_POST['categoria']),
    'dia' => testInput($_POST['dia']),
    'title' => testInput($_POST['title']),
    'subtitle' => testInput($_POST['subtitle']),
    'contenido' =>  $_POST['contenido'],
    'names' => testInput($_POST['names'])
);
$tags = $_POST['tag'];
$data = '';
$data_i = '';
$id = '$$';

/**
 *  sql strings para cada tabla...
 */
// (1) quiz ($sql)
$sql = "INSERT INTO `quiz` (`id`, `categoria_id`, `dia`, `title`, `subtitle`, `contenido`, `correcta`, `solucion`, `icon`) ";
$sql .= "VALUES (";
$sql .= "NULL, ";
$sql .= "'" . $resul['categoria'] . "', ";
$sql .= "'" . $resul['dia'] . "', ";
$sql .= "'" . $resul['title'] . "', ";
$sql .= "'" . $resul['subtitle'] . "', ";
$sql .= "'" . $resul['contenido'] . "', ";
$sql .= "NULL, NULL, ";
$sql .= "'" . $iniciales . "/ICONO.JPEG');";
$data = "<p class='w3-center'>Los datos del caso $iniciales se han guardado con éxito.<br/>";

// (2) quiz_images ($sql1)
/** 
 * ojo, siempre se pasa un S_FILES[] = 1 pero vacío..¡¡
 * la variable $id contendrá en principio un dummy value $$
 * que después será sustituido por el verdadero quiz_id
 */
if (count($_FILES) > 0 and $_FILES['imagenes']['name'][0] != '' ) {
    $imagenes = $_FILES['imagenes'];
    $data_i = "<table class='w3-table-all' style='width:60%; margin:auto'>";
    $data_i .= "<tr class='w3-theme'><th class='w3-center'>";
    $data_i .= "Se han guardado además la(s) imágen(es) siguiente(s):</th></tr>";

    $sql1 = "INSERT INTO `quiz_images` (`id`, `description`, `url`, `quiz_id`) VALUES ";
    // preparo las imágenes 
    for ($i = 0; $i < count($imagenes['name']); $i++) {
        /**
         * Para cargar las imágenes ordenadamente debemos componer
         * el nombre del archivo con un número, un guión alto y el nombre.
         * Ej.: 2-PIES ANT vascular.jpg (será la imagen número 2)
         * Nota: no usar underscores¡¡¡
         */
        $basename = explode('-', $imagenes['name'][$i]);
        $first = array_shift($basename);
        $imagenes['name'][$i] = implode(' ', $basename);
        
        // normalizo la extensión... jpg --> JPEG
        $basename = explode('.', strtoupper($imagenes['name'][$i]));
        $name = $basename[0];
        $ext =  $basename[1];
        if (substr($ext, 0, 2) === 'JP') {
            $ext = 'JPEG';
        }
        $basename = $name . '.' . $ext;
        $imagenes['name'][$i] = $basename;
        
        // amplio las imágenes a 512x512 salvo     
        // el icono que será a 128x128
        if (substr($name, 0, 5) === 'ICONO') {
            $ancho = 128;
            $alto = 128;
        } else {
            $ancho = 512;
            $alto = 512;
        }
        Image::open($imagenes['tmp_name'][$i])
            ->scaleResize($ancho, $alto)
            ->save($imagenes['tmp_name'][$i]);

        // recupero datos para el sql
        // OJO no incluyo el icono
        if ($basename != "ICONO.JPEG") {
            $sql1 .= "(NULL, ";        
            $sql1 .= "'$name', '$iniciales/$basename', '$id'), ";
            $data_i .= "<tr><td class='w3-center'>$name</td></tr>";            
        }
    }    
    $sql1 = substr($sql1, 0, strlen($sql1) - 2) . ';';
    $data_i .= "</table>";

    // ahora almaceno las imágenes
    // creo el directorio destino,...
    $uploaddir = IMAGES . $iniciales;

    if (!file_exists($uploaddir)) {
        if(!mkdir($uploaddir)) {
            $data = "Error<p class='w3-center'>No se ha podido crear el directorio $uploaddir. No puedo guardar el caso.</p>";
            echo $data;
            exit;
        }
    }
    for ($i = 0; $i < count($imagenes['name']); $i++ ) {
        $destino = $uploaddir . '/' . $imagenes['name'][$i];
        if (!move_uploaded_file($imagenes['tmp_name'][$i], $destino)) {
            $data = "Error<p class='w3-center'>Se ha producido un error al subir el archivo $destino.</p>";
            echo $data;
            exit;
        }
    }
    $data .= $data_i;
} else {
    $data = "Error<p class='w3-center'>No se han pasado imágenes. No puedo crear el caso nuevo.</p>";
    echo $data;
    exit;
}

// (3) Ahora los quiz_tags ($sql2) y los tags ($sql3)
$tagNuevo = false;
$sql2 = "INSERT INTO `quiz_tags` (`id`, `quiz_id`, `tag_id`) VALUES ";    
$sql3 = "INSERT INTO `tags` (`id`, `description`) VALUES ";

foreach ($tags as $value) {
    $pos = strpos($value, '-');
    if (!$pos) { 
        $sql2 .= "(NULL, '$id', '$value'),";
    } else {
        // tag nuevo
        $tagNuevo = true;
        $desc = substr($value, $pos + 1, strlen($value));
        $value = substr($value, 0, $pos);
        $sql2 .= "(NULL, '$id', '$value'),";
        $sql3 .= "('$value', '$desc'),";                    
    }
}   
// limpio el final
$sql2 = substr($sql2, 0, strlen($sql2)-1) . ';';
if ($tagNuevo) {
    $sql3 = substr($sql3, 0, strlen($sql3)-1) . ';';
}

/**
 * Store all...!!
 * Set parameters according to Host (local o web server)
 */
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $servername = 'localhost';
} else {
    $servername = 'mysql.hostinger.es';
}
$username = 'u525741712_quiz';
$password = 'XpUPQEthoAcKK5Y30b';    

try {
    $conn = new \PDO("mysql:host=$servername;dbname=u525741712_quiz", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    // paso el sql para el Quiz_caso
    $conn->exec($sql);
    // recupero el id del registro 
    $id = $conn->lastInsertId();        
    // paso el sql de las imágenes debe haber,..! 
    if (isset($imagenes)) {
        $sql1 = str_replace('$$', $id, $sql1);
        $conn->exec($sql1);
    }    
    // paso el sql de los tags y quiz_tags
    if ($tagNuevo) { 
        $conn->exec($sql3);
    }
    $sql2 = str_replace('$$', $id, $sql2);
    $conn->exec($sql2);
    echo $data;
}
catch(\PDOException $e) {
    echo "Error - Connection failed: " . $e->getMessage();
    exit;
}
$conn = null;
/*
function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}
*/
