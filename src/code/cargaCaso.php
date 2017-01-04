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
use function nuimsa\tools\getValores1;
 
require_once ROOT . DS . 'tools/tools.php';
//require_once './vendor/autoload.php';

/**
 * Procesa los datos par un caso Quiz nuevo:
 * el primer dato son las iniciales que 
 * servirán para el almacenamiento de las
 * imágenes.
 * Los 5 restantes son para la tabla Quiz
 * (incluye el icono).
 * Nota: el contenido puede ser HTML por lo
 * tanto no lo 'escapo'.
 * A continuación vienen las imágenes.
 */

$iniciales = strtoupper(htmlspecialchars($_POST['iniciales']));
$resul = array(
    'categoria' => htmlspecialchars($_POST['categoria']),
    'dia' =>  htmlspecialchars($_POST['dia']),
    'title' =>  htmlspecialchars($_POST['title']),
    'subtitle' =>  htmlspecialchars($_POST['subtitle']),
    'contenido' =>  $_POST['contenido']
);
$tags = $_POST['tag'];
$data = '';

// sql strings para cada tabla...
// (1) quiz
$sql = "INSERT INTO `quiz` (`id`, `categoria_id`, `dia`, `title`, `subtitle`, `contenido`, `correcta`, `solucion`, `icon`) ";
$sql .= "VALUES (";
$sql .= "NULL, ";
$sql .= "1, ";
$sql .= "'" . $resul['dia'] . "', ";
$sql .= "'" . $resul['title'] . "', ";
$sql .= "'" . $resul['subtitle'] . "', ";
$sql .= "'" . $resul['contenido'] . "', ";
$sql .= "NULL, NULL, ";
$sql .= "'" . $iniciales . "/ICONO.JPEG');";

// ojo siempre se pasa un S_FILES[] = 1 pero vacío..¡¡
if (count($_FILES) > 0 and $_FILES['imagenes']['name'][0] != '' ) {
    $imagenes = $_FILES['imagenes'];
    $id = '$$';
    $data_i = "<table class='w3-table-all' style='width:60%; margin:auto'>";
    $data_i .= "<tr class='w3-theme'><th class='w3-center'>";
    $data_i .= "Se han guardado la(s) imágen(es) siguiente(s):<th/></tr>";

    $sql1 = "INSERT INTO `quiz_images` (`id`, `description`, `url`, `quiz_id`) VALUES ";

    // preparo las imágenes 
    for ($i = 0; $i < count($imagenes['name']); $i++) {
        
        // extraigo el número de posición
        // ojo se asume un guion '-' entre el número
        // y el nombre del archivo.
        // puede haber más guiones pues solo se quita
        // el primero.
        // Nota: no usar underscores¡¡¡
        $basename = explode('-', $imagenes['name'][$i]);
        $first = array_shift($basename);
        $imagenes['name'][$i] = implode(' ', $basename);
        
        // normalizo extensión... 
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
            ->resize($ancho, $alto)
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
}
    
/**
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
        
    $data = "<p class='w3-center'>Los datos del caso $iniciales se han guardado con éxito.<br/>";
    
    // ahora cargo las imágenes
    if (isset($imagenes)) {
        // paso el sql para las imágenes si hay imágnes..?
        $sql1 = str_replace('$$', $id, $sql1); 
        $conn->exec($sql1);        

        // creo el directorio destino,...
        $uploaddir = "learning/images/$iniciales";

        if (!file_exists($uploaddir)) {
            if(!mkdir($uploaddir)) {
                $data = "Error<p class='w3-center'>No se ha podido crear el directorio $uploaddir. No puedo guardar las imágenes del caso.</p>";
                echo $data;
                exit;
            }
        }
        
        for ($i = 0; $i < count($imagenes['name']); $i++ ) {
            $destino = $uploaddir . '/' . $imagenes['name'][$i];
            if (!move_uploaded_file($imagenes['tmp_name'][$i], $destino)) {
                $data = "Error<p class='w3-center'>Se ha producido un error al subir el archivo $destino.</p>";
            }
        }
        $data .= $data_i;
    }

    // por ultimo voy a añadir los tags...
    // pero debo saber si hay nuevos,... antes de nada...
    // cargo los tags actuales y los comparo con cada uno de los
    // pasados,... seguro que se puede mejorar...
    // a la vez que guardo los tags voy creando el sql para
    // quiz_tags...
    //
/*
    $id_s = array();
    
    $sql = "INSERT INTO `quiz_tags` (`id`, `quiz_id`, `tag_id`) VALUES ";    
    $sql1 = "INSERT INTO `tags` (`id`, `description`) VALUES ";
    $resul = getValores1('tags', 'description');
    foreach ($tags as $key => $tagvalue) {
            if(!in_array($tagvalue, $resul)) {
                // tag nuevo lo debo guardar
                $sql1 .= "(NULL, $tagvalue);";
                $conn->exec($sql1);
                // recupero el id del registro 
                $id_[] = $conn->lastInsertId();
            } else {
                // estaba lo paso sin problemas
            }
    }
    
    
    
    
    foreach ($tags as $key => $value) {
        $sql .= "(NULL, $id, $value),";        
    }
    // limpio el final
    $sql = substr($sql, 0, strlen($sql)-1) . ';';
    
    // paso el sql 
    $conn->exec($sql);
*/    
    //uff, me voy,...a la cama claro,..¡¡ late night version..¡¡
    
} catch (\PDOException $e) {
    echo $e->getMessage;    
}

$conn = null;
echo $data;

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
?>