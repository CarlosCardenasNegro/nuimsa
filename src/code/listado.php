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

require_once ROOT . DS . 'tools/tools.php';

/**
 * listado.php - recupera el listado de casos de interés y demás
 * de acuerdo a la selección del usuario.
 * También se usa para recuperar por ejemplo la lista de categorías
 * para el menu,... (en este caso paso un GET con lo que queremos
 * listar) 
 */

if (isset($_POST['seleccion'])) {
    $seleccion = testInput($_POST['seleccion']);
    if ($seleccion != "7") {
        // por categoría
        $sql1 = 'select quiz.id, quiz.dia, quiz.subtitle, quiz.icon FROM quiz WHERE categoria_id = :cate';
    } else {
        // todos
        $sql1 = 'select quiz.id, quiz.dia, quiz.subtitle, quiz.icon FROM quiz';        
    }
    $value = false;
    $desc = testInput($_POST['descripcion']);
    
} else {
    if (isset($_GET['value'])) {
        $value = true;
        $param = testInput($_GET['value']);
        $sql = 'select ' . $param . '.* FROM ' . $param;
    } else {
        echo 'Error'; 
        exit;        
    }
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

    if (!$value) {        

        if ($seleccion === "7") {
            
            // recupero la consulta de Todos los casos
            $stmt = $conn->query($sql1);    
            // set the resulting array to associative
            $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
            $quiz = $stmt->fetchAll();
        
            $data = "<p class='w3-large w3-center' style='margin-bottom: 0px'>Se muestran TODOS los casos. Seleccione el que busca de entre ellos:</p>";

        } else {

            // recupero solo los casos de la categoría
            $stmt = $conn->prepare($sql1);
            $stmt->bindParam(':cate', $seleccion);
            $stmt->execute();
            // set the resulting array to associative
            $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
            $quiz = $stmt->fetchAll();        

            $data = "<p class='w3-large w3-center' style='margin-bottom: 0px'>Se muestran los casos de la categoría <i>'$desc'</i>. Seleccione el que busca de entre ellos:</p>";
        }
        
        // había..?
        if (count($quiz) === 0) {
            echo 'Error!'; 
            exit;
        }
        $data .= '<div id="quiz_listado" class="w3-padding-xlarge w3-border-top">';
        $data .= '<table class="w3-table-all w3-medium w3-card-8" onclick="showCaso(event)">';
        $data .= '<thead><tr class="w3-center">';
        $data .= '<th class="w3-theme-d3">Id</th>';
        $data .= '<th class="w3-theme-d1">Fecha</th>';
        $data .= '<th class="w3-theme-d3">Motivo</th>';
        $data .= '<th class="w3-theme-d1">Icono</th>';
        $data .= '<th class="w3-theme-d3">Acciones</th>';
        $data .= '</tr></thead><tbody>';
        
        foreach ($quiz as $value) {
            $src = NUIMSA . 'learning/images/' . $value['icon'];            
            $tmp = \date_create_from_format('Y-m-d', $value['dia']);
            $fecha = $tmp->format('d-m-Y');
            
            $data .= '<tr class="w3-hover-theme:hover">';
            $data .= '<td>' . $value['id'] . '</td>';
            $data .= '<td>' . $fecha . '</td>';
            $data .= '<td>' . $value['subtitle'] . '</td>';
            $data .= '<td><img class="w3-card-4"src="' . $src . '"  /></td>';
            $data .= '<td><button class="w3-btn w3-wide w3-theme-d1 w3-center w3-margin-top">Editar</button><br/>';
            $data .= '<button class="w3-btn w3-wide w3-theme-d4 w3-center w3-margin-top">Borrar</button></td></tr>'; 
        }
        $data .=  '</tbody></table></div><br/>';
        echo $data;
        
    } else {

        // recupero la consulta concreta 'a pelo'
        $stmt = $conn->query($sql);
        
        // set the resulting array to associative
        $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
        $resul = $stmt->fetchAll();
        
        // había..?
        if (count($resul) === 0) {
            echo 'Error!'; 
            exit;
        }
        
        echo json_encode($resul);        
    }
    $conn = null;        
    return;    
}

catch(\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}    

?>