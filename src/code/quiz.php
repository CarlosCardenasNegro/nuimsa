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

use function nuimsa\tools\getUserName;
use function nuimsa\tools\hex2rgba;
use function nuimsa\tools\getColor;
use function nuimsa\tools\hex2rgba;

require_once ROOT . DS . 'tools/tools.php';

/**
 * quiz.php - recupera el caso seleccionado y lo muestra
 *
 * Puedo mostrar:
 * 1. un paciente (GET id paciente)
 * 2. la imagen del dia básica (es decir la última que haya
 *    que es el default al inicio de la app)
 * 3. editar los casos usando los botones de EDICION Y BORRADO..¡¡
 *    en este caso se pasará en el POST como $_POST['boton'] que contendrá
 * - '' ... vacío ... no se pulsó botón
 * - 'Editar' ... edición o
 * - 'Borrar' ... borrado
 */

$salida = '';
$query = '';
$modo = htmlspecialchars($_POST['modo']);
$sql1 = '';
$sql2 = 'select quiz_images.* from quiz_images WHERE quiz_images.quiz_id = :paciente';
        
switch ($modo) {
    case 'dia':
        // selecciona la 'ultima imagen del dia que haya'
        $sql1 = "select quiz.* FROM tags LEFT JOIN quiz_tags ON quiz_tags.tag_id = tags.id LEFT JOIN quiz ON quiz_tags.quiz_id = quiz.id WHERE (tags.description LIKE \"Imagen del dia\") ORDER BY quiz.dia DESC LIMIT 1";    
        break;    
    case 'display':
        $param = htmlspecialchars($_POST['casoID']);
        //$w3dialogTitle = 'Imagen de interés';
        $sql1 = 'select quiz.* FROM quiz WHERE id = :paciente';    
        break;
    case 'editar':
        // aun no implementado
        $data = 'Salir';
        break;
    case 'borrar':
        $param = htmlspecialchars($_POST['casoID']);
        // pendiente de CASCADE,...
        // por ahora a lo bruto
        $sql1 .= "delete FROM quiz_images WHERE quiz_id = '$param'; ";
        $sql1 .= "delete FROM quiz_tags WHERE quiz_id = '$param'; ";
        $sql1 .= "delete FROM quiz WHERE id = '$param'; ";

        $data = 'Salir';
        break;        
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
    
    switch ($modo) {
        
        case 'dia':
            // muestro el caso del día 
            $stmt = $conn->query($sql1);        
            // set the resulting array to associative
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);        
            // recupero el caso
            $quiz = $stmt->fetchAll();
            // había..?
            if (count($quiz) === 0) { echo 'Error'; exit; }            
            break;
            
        case 'display':
            // caso seleccionado del listado
            $slot = ':paciente';
            $stmt = $conn->prepare($sql1);
            $stmt->bindParam($slot, $param);
            $stmt->execute();
            // set the resulting array to associative
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            // recupero el caso
            $quiz = $stmt->fetchAll();
            // había..?
            if (count($quiz) === 0) { echo 'Error'; exit; }            
            break;
            
        case 'borrar':
            // voy a borrar el registro y todos los relativos
            // más adelante veré lo de CASCADE...
            $conn->exec($sql1);
            // nos vamos            
            echo $data;
            exit; 
            
        case 'editar':
            // pendiente de programar
            // nos vamos            
            echo $data;
            exit; 
    }
    
    // todo ha ido bien ahora a por la
    // segunda consulta: patient images
    $images = array();
    $stmt = $conn->prepare($sql2);        
    $stmt->bindParam(':paciente', $quiz[0]['id']);
    $stmt->execute();
    // set the resulting array to associative
    $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
    $images[] = $stmt->fetchAll();
    
    $conn = null;
    
    // recupero el color "desvaido"...
    $colorHex = getColor('css/colores/' . $_COOKIE['colores'], "w3-theme-d2");
    $colorFin = hex2rgba($colorHex, '.8');
    
    // salida a pantalla
    $salida .= '<div id="day" class="w3-row w3-dialog" style="background:' . $colorFin . '">';
    $salida .= '<!-- Quiz -->';
    $salida .= '<section id="learning" class="w3-half w3-padding-large">';
    $salida .= '<h2 class="w3-center">' . $quiz[0]['title'] . '</h2>';
    $salida .= '<h3 class="w3-center">' . $quiz[0]['subtitle'] . '</h3>';
    $salida .= '<div class="w3-theme-d2 w3-border w3-padding">';
    $salida .= '<p>' . $quiz[0]['contenido'] . '</p>';
    $salida .= '</div>';
    $salida .= '</section>';
    $salida .= '<!-- Imagenes -->';
    $salida .= '<section id="carrousel" class="w3-half">';
    $salida .= '<div id="div-left">';
    $salida .= '<span id="btn-left" onclick="goBackward()">&lt;</span>';
    $salida .= '</div>';
    $salida .= '<div id="div-img">';

    $cont = 0;
    foreach($images[0] as $key => $image) {
        $id = 'img' . strval($cont += 1);
        $src = './learning/images/' . $image['url'];
        $style = ($cont === 1 ? 'display: block' : 'display: none');
        
        if (strtoupper(substr($src , -4, 4)) === 'JPEG') {
            $salida .= '<img id="' . $id . '" class="div-img" src="' . $src . '" style="' . $style . '"/>';
        } else {
            $salida .= '<!-- si la imagen es un video lo muestro así -->';
            $salida .= '<video id="' . $id . '" class="div-img" style="' . $style . '" autoplay loop>';
            $salida .= '<source src="' . $src . '" type="video/mp4">';
            $salida .= '</video>';
        }
        $salida .= '<!-- debajo de cada imagen va la descripcion -->';
        $salida .= '<h2 id="' . $id . '" style="' . $style . '">' . $image['description'] . '</h2>';                
    }
    $salida .= '</div>';
    $salida .= '<div id="div-right">';
    $salida .= '<span id="btn-right" onclick="goForward()">&gt;</span>';
    $salida .= '</div>';
    $salida .= '<div id="div-bottom">';

    $cont = 0;
    $total = count($images[0]);
    $medio = round($total / 2);
    $base = 45;
                            
    for ($i = 1; $i <= $medio; $i++) {
        $id = 'marks' . strval($cont += 1);
        $style = 'left: ' . strval($base - (($medio - $i) * 5)) . '%;';
        $style .= ($i === 1 ? ' background: red' : ' background: white');                

        $salida .= '<p id="' . $id . '" class="marks" style="' . $style . '" onclick="markClick( Number(this.id.substr(5)))"></p>';
    }
    $cont = $medio - 1;
    for ($i = $medio + 1; $i <= $total; $i++) {
        $id = 'marks' . strval($i);
        $style = 'left: ' . strval($base + (($medio - $cont) * 5)) . '%; background: white';

        $salida .= '<p id="' . $id . '" class="marks" style="' . $style . '" onclick="markClick( Number(this.id.substr(5)))"></p>';
        $cont -= 1;
    }
    $salida .= '</div>';
    $salida .= '</section>';
    $salida .= '</div>';
    $salida .= '</div>';

    echo $salida;
}
catch(\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>