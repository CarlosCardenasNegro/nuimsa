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
namespace nuimsa\tools;

/**
 * Load constants...if neccesary
 */
 if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
}

require_once ROOT . DS . 'vendor/autoload.php';

// declaro clases
use nuimsa\clases\Demograficos;
use nuimsa\clases\Solicitud;
use nuimsa\clases\Acude_por;
use nuimsa\clases\Localiza;
use nuimsa\clases\Evolucion;
use nuimsa\clases\Califica;
use nuimsa\clases\Motivo;
use nuimsa\clases\Clinica;
use nuimsa\clases\Incidencias;
use nuimsa\clases\SDRC;
use nuimsa\clases\Localizacion_protesis;
use nuimsa\clases\Lateralidad_protesis;
use nuimsa\clases\Evolucion_protesis;

/**
 * tools.php - Contiene la mayor parte de las funciones de la app.
 */

/**
 * Casos especiales en los que paso un POST como nombre de función (dispatching)
 * puedo pasar parámetros como param=1,ejemplo,45.875867,otro ejemplo,etc,...
 * los parámetros se pasarán como una string separados por comas
 * luego los paso a un array param[]. OJO, seguirán siendo strings,...¡
 * es mi responsabilidad pasarlos a su valor esperado antes de su uso..¡¡
 */
if (isset($_POST['name'])) {
     $name = testInput($_POST['name']);
     if(isset($_POST['param'])) {
         $param = explode(',', testInput($_POST['param'])); 
     }
     
     switch ($name) {
         case 'sapID':
             // devuelve el código SAP a partir de la exploración         
            if (!isset($_POST['explo'])) {
                return;
            } else {
                $buscado = testInput($_POST['explo']);
            }
    
            global $explo;
            
            // a lo bruto,...
            foreach ($explo as $key => $value) {
                if ($value['desc_larga'] === $buscado) {
                    echo $value['SAPid'];
                }
            }         
            break;
            
         case 'lookup':
            // devuelve la palabra completa tras hacer
            // un lookup con la tabla u525741712_lookup
            $sql = "SELECT 'word_result' FROM `lookup` WHERE `word_original` LIKE :palabra";
             
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
                $conn = new \PDO("mysql:host=$servername;dbname=u525741712_lookup", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $slot = ':palabra';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam($slot, $param[0]);
                $stmt->execute();
                // set the resulting array to associative
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                // recupero la palabra correcta
                $correcta = $stmt->fetchAll();
                // había..?
                if (count($correcta) === 0) {
                    echo 'Error';
                } else {
                    echo $correcta[0]['word_result'];
                }
                $conn = null;        
            }
            catch(\PDOException $e) {
                echo "Error - Connection failed: " . $e->getMessage();
            }
            break;
            
         case 'nlp':
            echo nlp($param[0]);
            break;
     }
}

/********* PDO functions **************/

/**
 * Recupero los valores desde una tabla...¡¡
 *
 * He añadido la posibilidad o necesidad de pasar el 'id'
 * pues por lo general el 'id' se pasará al campo 'value'
 * y la 'description' al campo 'valores', ya que, las id no
 * tienen porque ser correlativas pues si borramos una no se recupera
 * su id y se saltan valores,...
 *
 * @param string $database Database en la que se encuentra la tabla
 * @param string $tabla Nombre de la Tabla donde se encuentran los valores
 * @param string $campo Nombre del campo a recuperar
 */
function getValores1($database, $tabla, $campo) {
        
    $sql = "select $campo FROM $tabla";
    
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
        $conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        foreach ($conn->query($sql) as $row) {
            $result[] = $row;                
        }
        return $result;
        $conn = null;        
    }
    catch(\PDOException $e) {
        return "Connection failed: " . $e->getMessage();
    }        
} 

/**
 * setValores - Establece (insert) una serie de valores
 * en una tabla.
 *
 * @param string $tabla Nombre de la tabla
 * @param array $valores Array asociativo $valores['fieldname'] = 'value'
 * @return true/false en el caso de acierto/error
 */
 function setValores($tabla, $valores) {
    
    /**
     * El ID será el primer campo
     * ira NULL y lo paso por defecto
     */
    $sql = "INSERT INTO `$tabla` (`id`, ";
    // fieldnames
    foreach ($valores as $key => $value) {
        $sql .= "`$key`, ";        
    }
    $sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= ") VALUES (NULL, ";
    // values
    foreach ($valores as $key => $value) {
        $sql .= "'$value', ";        
    }
    $sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= ");";
    
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
        $conn = new \PDO("mysql:host=$servername;dbname=u525741712_pacientes", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $conn->exec($sql);
        $conn = null;
        return true;
    }
    catch(\PDOException $e) {
        return "Connection failed: " . $e->getMessage();
    }
 }
 
 /************** USER INTERFACE FUNCTIONS ******************/
 
/**
 * Muestra un table con la lista de pacientes contenido en el 
 * nodeList pasado y, opcionalmente,  devuelve el
 * seleccionado rellamando a la pagina con un id que contendrá
 * los datos para localizarlo que serán la suma de ini
 * + fec + exp que identificarían univocamente a un 
 * paciente.
 * tengo que pensar en que hacer cuando el usuario no selecciona nada
 * quizás pulsando ESC. Supongo que lo suyo es volver a
 * inicio.html ... sera con javascript...
 * 
 * @param DOMNodeList $nodos
 */
function muestraListado($nodos, $fecha) {
    
    $listado = '<table class="w3-table-all w3-large w3-theme-l4">';
    $listado .= '<thead>';
    $listado .= '<tr><th class="w3-theme-d3 w3-center">Iniciales</th><th class="w3-theme-d1 w3-center">Exploración realizada</th></tr>';
    $listado .= '</thead>'; 
    
    // -----------------
    // relleno los datos
    // -----------------
    foreach($nodos as $paciente) {

        $ini = $paciente->childNodes->item(0)->nodeValue; //iniciales
        $fec = $paciente->childNodes->item(1)->nodeValue; //fecha
        $exp = $paciente->childNodes->item(3)->nodeValue; //exploracion

        $fun = "$( '#xx0304' ).fadeTo('slow', 0).hide();$( '#dialogo' ).html('');";
        $fun .= "cargaPag('entrevista.php?ini=$ini&fec=$fec&exp=$exp')";
        
        $listado .= '<tr>';
        $listado .= '<td style="cursor:pointer; color:red" onclick="' . $fun . '">' . $ini . '</td>';
        $listado .= '<td>' . $exp . '</td></tr>';
    } 
    // -----------------
    $listado .= '</table>';

    return $listado;
}

/**
 * Crea el formulario de entrevista clínica completo.
 * Las secciones se crean llamando a la función miembro de cada
 * clase toHTML().
 *
 * @param array $classArray de clases ¡¡¡ inicializadas previamente !!!
 * @param array $estatus Estado de display para las secciones (1, 0)
 * @return salida a pantalla completa
 */
function creaSecciones($classArray, ...$param) {

    $title = "&nbsp;Entrevista clínica básica";
    $banner = "";
    // salida a pantalla de la cabecera estándar
    ?>
    <!-- modal -->
    <div class="popup">
        <!-- cabecera estándar -->
        <div id="inicio" class="w3-theme-d3 w3-padding shadow w3-hide-medium w3-hide-small" style="width: 80%; margin:auto; border-radius: 10px 10px 3px 3px; border-bottom: 3px solid black">
            <p class="w3-lato w3-center" style="font-size: 4.5em"><i class="w3-jumbo fa fa-eye"></i><?= $title ?></p>
            <p class="w3-lato w3-center" style="font-size: 2.5em"><?= $banner ?></p>
        </div>
    
        <!-- cuerpo del formulario -->
        <div id="entrevista" class="w3-container w3-theme-l4">
            <div id="subentrevista" class="w3-theme-l3 w3-round-large" style="padding: 4px">
                <form id="form" enctype="multipart/form-data">
                    <!-- salida a pantalla de las secciones -->
                    <?php
                    if(func_num_args() === 1) {
                        $tatus = array (1,1,1);
                        for ($i = 3; $i < count($classArray); $i++) {
                            $tatus[] = 0;
                        }
                    } else {
                        $tatus = $param[0];
                    }
                    
                    $on  = "display: block";
                    $off = "display: none";
                    for ($i = 0; $i < count($classArray); $i++) {
                        $classArray[$i]->toHTML( $tatus[$i] ? $on : $off );                
                    }
                    ?>
                </form><br/>
            </div>
            <div style="margin-top: 6%">
                <button class="w3-btn w3-wide w3-theme-d1 w3-half w3-w3-hover-theme w3-hover-theme:hover" id="submit" onclick="sendData()">Envia formulario</button>
                <button class="w3-btn w3-wide w3-theme-d5 w3-half w3-w3-hover-theme w3-hover-theme:hover" id="cancel" onclick="cargaPag('inicio.php')">Cancelar</button>
                <p/>
            </div>            
        </div>
    </div>
    <br/>
    <?php            
}

/****************** CLASSES FUNCTIONS *****************/

/**
 * Inicializa las clases a valores default o a valores pasados en $param[0] 
 * Nota: por ahora solo la uso para el $_POST
 *
 * @param array $param Si pasado contiene los valores para inicializar las clases
 * habitualmente el array pasado será un $_POST (en este caso le añado un flag
 * para poder saberlo...
 * @param boolean $flag True si el origen es $_POST, False si no...
 * @return array $lista Contiene un array con las clases inicializadas.
 */
function initClases(...$param) {

    $lista = array();

    // inicio a base    
    $lista[] = new Demograficos();
    $lista[] = new Solicitud();
    $lista[] = new Acude_por();
    $lista[] = new Localiza();
    $lista[] = new Califica();
    $lista[] = new Evolucion();
    // 
    $lista[] = new Motivo();
    $lista[] = new Clinica();
    //$lista[] = new Adjunto();
    $lista[] = new Incidencias();
    $lista[] = new SDRC(); 
    // 
    $lista[] = new Localizacion_protesis();
    $lista[] = new Lateralidad_protesis();
    $lista[] = new Evolucion_protesis("derecha");
    $lista[] = new Evolucion_protesis("izquierda");
    
    /** si se pasaron datos los relleno
     * Nota: imito al efecto de serialize sobre un Form
     * pasando campos tipo:
     * - Text, Textarea, Date, Hidden aunque esten vacios
     * - Radio con su valor y 
     * - Checkbox (ojo¡, la primera vez que viene del
     *  $_POST lo pongo a 'on'. La segunda vez que es
     * al editarlo YA vendrá con el 'on'. Para que esto
     * funcione debo saber cuando lo que paso para relleno
     * es un S_POST o no,... pondré un flag hasta que se 
     * me ocurra algo mejor...:-(...
     */
    $a = count($param);
    if (count($param) > 0) {
        for ($i = 0; $i < count($lista); $i++) {
            foreach ($lista[$i]->campos as $key => $value) {
                if (array_key_exists($key, $param[0])) {
                    switch ($value['type']) {
                        case 'checkbox':
                            // paso $flag..?
                            if (count($param) === 2) {
                                if ($param[1]) {
                                    $lista[$i]->$key = 'on';                                    
                                }
                            } else {
                                if ($param[0][$key] === 'on') {
                                    $lista[$i]->$key = 'on';
                                }
                            }
                            break;
                        case 'radio':
                            if (!empty($param[0][$key])) {
                                $lista[$i]->$key = $param[0][$key];            
                            }
                            break;
                        case 'date':
                            $lista[$i]->$key = convierteFecha($param[0][$key], 'local');
                            break;
                        default:
                            $lista[$i]->$key = $param[0][$key];            
                    }
                }    
            }
        }
    }

    return $lista;
}

/******************** DOM MANIPULATION *******************/

/**
 * Pasado un paciente (como DOMNode) recupero los valores previos 
 * para que las secciones se muestren ya rellenas.
 * ojo, el DOMNode a usar es el parent pues se 
 * recupera solo el nodo demograficos.
 * 
 * @param DOMNode $paciente
 * @return array
 */
function getDOMValores($paciente) {
    $padre = $paciente->item(0)->parentNode;
 
    foreach($padre->childNodes as $cab) {
        foreach ($cab->childNodes as $value) {
            $valores[$value->nodeName] = (string) $value->nodeValue;
        } 
    }
    return $valores;
}

/**
 * Busca en el $archivo un paciente(s) y devuelve un
 * DOMNodeList con el resultado que puede ser:
 * 
 * 0, vuelvo a inicio
 * 1, lo devuelvo
 * >1, muestro una tabla para selección
 * 
 * @param DOMDocument $arbol el documento de partida
 * @param string $path el xpath
 * @return DOMNodeList o nada (item(0) = empty)
 */
function buscaPacientes(&$arbol, $path) {
 
    // creo un objeto XPath
    $xpath = new \DOMXpath($arbol);
    $res = $xpath->query($path);

    return $res;
}

/**
 * Borra un paciente del Node Tree (usando removeChild)
 * 
 * @param string $archivo
 * @param DOMNodeList $oldNode (en este caso solo contiene UN item...
 * @return DOMNode paciente eliminado
 */
function borraPaciente($oldNode) {
 
    $lista = $oldNode; //Nodelist
    $nodo = $lista->item(0);
    $paciente = $nodo->parentNode;
    $pacientes = $paciente->parentNode;
    
    return $pacientes->removeChild($paciente); 
}

/**
* Rellena las clases a partir de un
* DOMNode <paciente>. Al igual que
* antes lo que paso es <Demograficos>...
*
* @param DOMNode $paciente  DOMNode que contiene a un paciente
*/
function xmlToClases ($paciente) {
  
  // creo las clases
  $classArray = initClases();
  
  // recupero al padre (el paciente real)
  $padre = $paciente->item(0)->parentNode;
 
  $x = 0;   //contador de clases
  foreach($padre->childNodes as $cab) {
    foreach ($cab->childNodes as $value) {
      $name = $value->nodeName;
      $valor = $value->nodeValue;      
      $classArray[$x]->$name  = $valor;
   }
   $x += 1;
 }
 return $classArray; 
}

/**
 * Creo un DOMNode <paciente> a partir de 
 * las clases y lo añade al archivo XML.
 *
 * @param DOMDocument $dest Documento de pacientes
 * @param array $classArray Array conteniendo las clases rellenas
 */
function clasesToXml ($dest, $classArray) {
    //$DOMArray = array();

    //$archivo = './xml/datos_pacientes.xml';
    //$doc = new DOMDocument('1.0', 'UTF-8');
    //$doc->load ($archivo) or die('No he podido cargar el archivo de pacientes¡');

    $x = 0;
    $src = new \DOmDocument('1.0', 'UTF-8');
    $src->loadXML('<paciente/>');

    foreach ($classArray as $key => $value) {
        $nodo = $src->importNode($value->toDOMNodes(), true);
        $src->documentElement->appendChild($nodo);
    }

    $nodo = $dest->importNode($src->documentElement, true);
    $dest->documentElement->appendChild($nodo);
    $dest->save($dest->documentURI);
    //$dest->save($archivo);
}

/****** UTILS FUNCTIONS ****/

/**
 * Recupera el valor hex del
 * color actual en uso desde
 * el archivo css
 *
 * @param string $css Nombre del archivo css conteniendo el tema de los colores actuales
 * @param string $searchColor Nombre de la clase del color buscado (w3-theme-l1,...)
 * @return string $color El color en hexadecimal como string #FFFFFF.
 */
function getColor ($css, $searchColor) {
    // pasada la hoja de estilo 
    // debería recuperar el color real
    $cssFile = WWW_ROOT . $css;
    $lines = file($cssFile);

    for ($i = 0; $i < count($lines); $i++) {
        if(substr($lines[$i], 0, 12) === '.' . $searchColor) {
            // recupero el color
            $color = explode('#', $lines[$i]);
            $color = '#' . substr($color[2], 0, 6);
            break;
        }
    }    
    return $color;
}

/**
 * Convert hexdec color string to rgb(a) string 
 * credits to: http://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
 *
 * @param string $color Hexadecimal string color
 * @param string $opacity Valor de opacidad (RGBA)
 * @return string $output Cadena rgba() 
 */

function hex2rgba($color, $opacity = false) {
 
    $default = 'rgb(0,0,0)';
    
    //Return default if no color provided
    if(empty($color)) {
        return $default;        
    }
    
    //Sanitize $color if "#" is provided 
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }
    
    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }
    
    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);
    
    //Check if opacity is set(rgba or rgb)
    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
    }
    
    //Return rgb(a) color string
    return $output;
}

/**
 * Convierte entre fecha Local (dd/mm/yyyy) e ISO-Chrome (yyyy-mm-dd)
 * 
 * @param string $fecha Fecha a convertir
 * @param string $modo  Modo de conversión ('local', 'ISO')
 */
function convierteFecha ($fecha, $modo) {
    if (empty($fecha)) { return ''; }
    
    if (strtolower($modo) === 'local') {
        if ($temp = date_create_from_format('d/m/Y', $fecha)) {
            // la fecha está en modo local, la devuelvo intacta
            return $fecha;
        } else {
            // se paso en modo Chrome, la convierto a local
            $temp = date_create_from_format('Y-m-d', $fecha); 
            return $temp->format('d/m/Y');
        }
    } else {
        if ($temp = date_create_from_format('Y-m-d', $fecha)) {
            // la fecha está en modo ISO, la devuelvo intacta
            return $fecha;
        } else {
            // se paso en modo local, la convierto a ISO
            $temp = date_create_from_format('d/m/Y', $fecha);
            return $temp->format('Y-m-d');
        }
    }
}

/**
 * Sencilla función que rodea con una '
 * las variables pasadas a html
 * 
 * @param string $var variable a rodear
 * @return string variable entrecomillada
 */
function comas(&$var) {
 // chorrada
 return '"' . $var . '"';
}

/**
 * Testea los valores que han venido en 
 * el POST o GET para seguridad...
 *
 * @param string $data   Datos recibidos
 * @return string $data  Datos ya filtrados
 */
function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Autentifica al usuario que entra
 *
 */
function autenticar() {
    header ('WWW-Authenticate: Basic realm="Sistema de autenticación"');
    header ('HTTP/1.0 401 Unauthorized');
    echo "Debe introducir un ID y contraseña de identificación válidos para acceder a la Web de NMToolkit\n";
    exit;
}

/**
 * Compara el usuario que entra
 * con los usuarios posibles
 *
 * @return array $password Password de los usuarios
 */
function getUsers() {
    $password = array();
    
    $filename = XML . "users.xml";
    $doc = new \DOMDocument("1.0","UTF-8");
    $doc->load($filename);

    foreach ($doc->documentElement->childNodes as $usuarios) {
        $password[$usuarios->childNodes->item(0)->nodeValue] = $usuarios->childNodes->item(1)->nodeValue;  
    }
    return $password;
}

/**
 * Recupero el nombre del usuario actualmente Logged
 *
 * @param string $user Nombre de Login del usuario
 * @return string Nombre completo del usuario
 */
function getUserName($user) {
    
    $filename = XML . "users.xml";
    $doc = new \DOMDocument("1.0","UTF-8");
    $doc->load($filename);    
    $x = "/usuarios/usuario[nombre = '" . $user . "']";
    $resul = buscaPacientes($doc, $x);

    return $resul->item(0)->childNodes->item(2)->nodeValue;  
}

/**
 * Convierte el texto pasado a minúsculas
 * respetando la mayúscula inicial tras un punto y seguido
 *
 * @param $frase string Frase a convertir.
 * @return $res string Frase convertida. 
 */
function convierteMinusculas($frase) {
    if (empty($frase)) { return; }
    if (strlen($frase) == 1) { return $frase; }    
    
    $res = '';

    $cadenas = explode('.', $frase);
    switch (count($cadenas)) {
        case 0: 
            // no había punto de separación
            // la paso a minúsculas y le añado
            // un punto al final
            $cadena = strtolower($cadena) . '.';
            break;
        case 1:
            // hay al menos un punto final
            $cadena = strtolower($cadena);
            break;
        default:
            // hay dos o más puntos
            foreach ($cadenas as $cadena) {
                $cadena = trim($cadena);
                $inicial = strtoupper(substr($cadena, 0, 1));
                $resto = strtolower(substr($cadena, 1, strlen($cadena) - 1));
                $res .= $inicial . $resto . '. ';
            }
    }
    return trim($res);
}

/**
 * Paso una frase completa de texto
 * analizada palabra por palabra
 * no se si será demasiado..¡¡???
 * 
 * @param string $cadena Frase pasada por referencia a analizar.
 */
function nlp($cadena) {

    $items = explode(' ', $cadena);
    
    // recupero el diccionario...
    $dict = getValores1('u525741712_lookup', 'lookup', 'word_original, word_result');
    // creo los arrays
    foreach ($dict as $value) {
        $item_buscado[] = $value[0];
        $item_cambiado[] = $value[1];
    }
    // reemplazo
    return str_replace($item_buscado, $item_cambiado, $cadena);
}