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
 */
if (isset($_POST['name'])) {
     $name = $_POST['name'];
     
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
     }
}

/**
 * Recupero los valores desde una tabla...¡¡
 *
 * He añadido la posibilidad o necesidad de pasar el 'id'
 * pues por lo general el 'id' se pasará al campo 'value'
 * y la 'description' al campo 'valores', ya que, las id no
 * tienen porque ser correlativas pues si borramos una no se recupera
 * su id y se saltan valores,...
 *
 * @param string $tabla Nombre de la Tabla donde se encuentran los valores
 * @param string $campo Nombre del campo a recuperar
 */
function getValores1($tabla, $campo) {
        
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
        $conn = new \PDO("mysql:host=$servername;dbname=u525741712_quiz", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        foreach ($conn->query($sql) as $row) {
            $result[] = $row[0];                
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
 * Inicializa las clases a valores default o a valores pasados en $param[0] 
 * Nota: por ahora solo la uso para el $_POST
 *
 * @param array $param Si pasado contiene los valores para inicializar las clases
 * habitualmente el array pasado será un $_POST (en este caso le añado un flag
 * para poder saberlo...
 * 
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
 * Crea el formulario de entrevista clínica completo.
 *
 * Las secciones se crean llamando a la función miembro de cada
 * clase toHTML().
 *
 *
 * @param array $classArray de clases ¡¡¡ inicializadas previamente !!!
 * @param array $estatus Estado de display para las secciones (1, 0)
 *
 * @return salida a pantalla completa
 *
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
                <button class="w3-btn w3-wide w3-theme-d1 w3-half w3-w3-hover-theme w3-hover-theme:hover" id="submit" onclick="sendData('')">Envia formulario</button>
                <button class="w3-btn w3-wide w3-theme-d5 w3-half w3-w3-hover-theme w3-hover-theme:hover" id="cancel" onclick="cargaPag('inicio.php')">Cancelar</button>
                <p/>
            </div>            
        </div>
    </div>
    <br/>
    <?php            
}

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
* Añade un paciente al documento XML.
*
* Es una nueva versión basada en las clases.
*
* @param array $classArray Array con las clases "rellenas"
*
*/
function creaDocGeneral($classArray) {
 $archivo = '../../xml/datos_pacientes.xml';
 
 $doc = new \DOMDocument("1.0","UTF-8");
 $doc->loadXML('<paciente/>');
 foreach ($classArray as $key => $value) {
  $nodo = $doc->importNode($value->toDOMNodes(), true);
  $doc->documentElement->appendChild($nodo);
 }
 
 $xmlDoc = new DOMDocument('1.0', 'utf-8');
 $xmlDoc->load ($archivo);
 $nodo = $xmlDoc->importNode($doc->documentElement,true);
 $xmlDoc->documentElement->appendChild($nodo);
 
 $xmlDoc->save($archivo);  
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
* 
*
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
function ConvierteMinusculas($frase) {
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
 * @deprecated since version 0.0
 */
//---------------------------
// TEMPORALMENTE DESECHADAS
//---------------------------
/**
 * Crea el cuerpo de cada pagina (Nota: la pagina debe terminar
 * con </article>). Podemos o no pasar un texto introductorio.
 *
 * @param string $textoCuerpo
 * @param string $icono
 * @param string $textoIntro
 */
function creaCuerpo($textoCuerpo, $icono, $textoIntro) {
 
 echo '<article class="w3-container w3-theme w3-padding-large">';
 echo '<h1 class="w3-center w3-jumbo w3-lato"> <i class="w3-jumbo ' . $icono . '"></i>  ' . $textoCuerpo . '</h1>'; 
 if (!empty($textoIntro)) {
  echo '<div class="w3-container w3-padding">';
  echo '<h2 class="w3-center w3-padding w3-lato" style="font-size: 3em !important">' . $textoIntro . '</h2>';
  echo '</div>';
 }
}

function creaDocLocal($resul, $archivo)
{
 // ahora guardo a este paciente en su propio dia
 // repetiría todo,...
 // si todo es correcto escribo los datos XML

 global $cabeceras;
 global $variables;
 
 if (!file_exists($archivo)) {
  $temp = fopen($archivo, "w") or die("Incapaz de crear el archivo");
  echo fwrite($temp, '<?xml version="1.0" encoding="utf-8"?><pacientes></pacientes>');
  fclose($temp);
 }
 
 $xmlDoc = new DOMDocument('1.0', 'utf-8');
 $xmlDoc->load ($archivo);
 
 $root = $xmlDoc->documentElement;
 $paciente = $xmlDoc->createElement('paciente');
 
 reset($resul);
 foreach ($resul as $key => $value) {
  $nodo = $xmlDoc->createElement($key, $value);
  $paciente->appendChild($nodo);
 }
 $root->appendChild($paciente);
 
 //$archivo = $resul["iniciales"] . '.xml';
 return $xmlDoc->save($archivo);
}



/*
// PARA MAS ADELANTE...
// lo envio a una cadena y luego mail...
$datos = simplexml_load_file($archivo) or die("Error:no puedo leer el archivo!!");
$datos = wordwrap($datos, 70);
$to = "familiacardenasdelgado@gmail.com";
$subject = "Ejemplo de datos de paciente";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/xml;charset=UTF-8" . "\r\n";
$headers .= "From: <carloscardenasnegro@gmail.com>" . "\r\n";
mail($to, $subject, $datos, $headers);
*/

// testeo de las variables
// ya no es útil pues la 
// validación es local
// y no muy estricta

/**
 * Muestra un mensaje de alerta 
 * 
 * @param string $id la #id del DIV
 * @param string $tipo el tipo de mensaje (ver debajo)
 * @param string $accion la función o acción a realizar además de la básica 
 * @param string $mensaje el mensaje que queremos mostrar
 * 
 * El $tipo puede ser:
 * Error!
 * color: w3-red icono: fa fa-exclamation-triangle
 * Atención!
 * color: w3-orange icono: fa fa-child
 * Exito!
 * color: w3-green icono: fa fa-check-circle
 * Info!
 * color: w3-blue icono: fa fa-info-circle
 * 
 * Notas: la linea de código que se añade al evento onclick debe 
 * incluir el ; inicial¡¡. Se puede dejar en blanco
 * para que se ejecute la función por defecto que será:
 * $( "' . $id . '" ).fadeTo("slow", 0).hide()
 * 
 * El mensaje a mostrar puede contener html formateado.
 *  
 */
function displayMensaje ($id, $tipo, $accion, $mensaje) {
    $fun = "$( '#$id' ).fadeTo('slow', 0).hide();$( '#dialogo' ).html('')" . $accion;
    switch ($tipo) {
        case "Error!":
            $icono = "fa fa-exclamation-triangle";
            $color = "red";
            break;
        case "Atención!":
            $icono = "fa fa-child";
            $color = "orange";
            break;
        case "Exito!":
            $icono = "fa fa-check-circle";
            $color = "green";
            break;
        case "Info!":
            $icono = "fa fa-info-circle";
            $color = "blue";
            break;   
 }
 // codigo html que crea la ventana
 ?>
 <div id='<?= $id ?>' class='w3-modal' style='display: block'>
     <div class='<?= "w3-modal-content w3-card-8 w3-round-xlarge w3-" . $color ?>'>
         <header class="w3-container w3-center w3-xlarge w3-padding">
             <span onclick="<?= $fun ?>" class="w3-closebtn">&times;</span><?= $tipo ?>
         </header>    
         <section class="w3-container w3-white w3-center w3-text-grey" style="border-radius: 0px 0px 15px 15px">
             <p class="w3-padding-xlarge">
                <i class='w3-xxxlarge w3-left w3-text-<?= $color ?> <?= $icono ?>'></i>
                <span class="w3-xlarge"><?= $mensaje ?></span>
             </p>
         </section>
     </div>
 </div>
 <?php
}


/**
 * Crea el grid de los consentimientos informados.
 * 
 * Crea la página de los consentimientos
 * informados usando un w3-grid con 4 
 * elementos por fila.
 * 
 * @author Carlos Cárdenas Negro (carloscardenasnegro@gmail.com)
 * @version 1.0
 * @copyright (c) San Miguel Software, Carlos Cárdenas Negro
 * 
 * @global array $explo Array conteniendo las exploraciones
 */
function creaConsentimientos() {

    global $explo;

    $cont = 0;
    //$com = '"';
    $dir_pdf = './pdf/';
    $dir_img = './images/';
    $color = array("w3-blue","w3-orange","w3-yellow", "w3-green");
    
    echo '<div class="w3-row-padding w3-margin-top">';   
    
    foreach ($explo as $key => $value) {
        if (!empty($value['CI'])) {   
            $cont += 1;        
            if ($cont >= 4) {
                // cada cuatro una fila nueva
                echo '</div>';
                echo '<div class="w3-row-padding w3-margin-top">';
                $cont = 0;
            }
   
           echo '<div class="w3-quarter">';
           echo '<div class="w3-card-2 ' . $color[$cont] . '"">';
           echo '<a href=' . comas($dir_pdf . $value['CI']) . ' target="_blank"><img src=' . comas($dir_img . $value['image']) . ' style="width:100%">';
           echo '<div class="w3-container">';
           echo '<h5 class="w3-center">' . $key . '</h5>';
           echo '</a>';
           echo '</div>';
           echo '</div>';
           echo '</div>';
       }
    }
    echo '</div>'; 
}

/**
 * Recupera la posición de la $cabecera pasada en $cab
 * como $key y los valores de Start y End de las variables
 * en $ini y $fin
 * 
 * @global type $cabeceras
 * @global type $variables
 *
 * @param string $cab cabecera a recuperara
 * @param string $key valor key
 * @param string $ini campo inicial
 * @param string $fin campo final
 */
function getCabecera($cab, &$key, &$ini, &$fin) {
 
    global $cabeceras;
    global $variables;
    
    $col = array_column($cabeceras,"title");
    $key = array_search($cab, $col);
    $ini = $cabeceras[$key]["start"];
    $fin = $cabeceras[$key]["end"];
}

/**
 * A partir de $arr recupera los campos de
 * $variables y los devuelve individuales
 * 
 * @param array $arr
 * @param string $desc
 * @param string $label
 * @param string $type
 * @param string $value
 * @param string $id
 */
function getVariables($arr, &$desc, &$label, &$type, &$value, &$id) {

    $res = array();
    while(list($key, $value) = each($arr)) { array_push($res, $value); }
    list($desc, $label, $type, $value, $id) = $res;
}


    
/**
 * creo un array con los resultados pasados en el $_POST
 * que luego será usado por la rutina que crea el documento XML.
 * 
 * @global type $cabeceras
 * @global type $variables
 * @return array <code>$key => $value</code>
 */
function creaArray() {
    // creo un array 
    // con los resultados pasados
    
    global $cabeceras;
    global $variables;
    
    $resul = array(); 
    
    // (2) Itero sobre variables...
    foreach ($cabeceras as $key) {
        // recupero cabecera y offset de las variables
        $index = $inicio = $fin = 0;
        $pagina = $key["title"];
        getCabecera($pagina, $index, $inicio, $fin);
        
        // para cada variable recupero su valor o nada
        for ($x = $inicio; $x <= $fin; $x++) {  
            getVariables($variables[$x], $desc, $label, $type, $value, $id);
            // se paso en el POST...?
            if (array_key_exists($desc, $_POST)) {
                switch ($type) {
                    case "checkbox":   
                        $resul[$desc] = 'on';
                        break;    
                    case "date":
                        $fechaRecambio = date_create($_POST[$desc]);
                        $resul[$desc] = date_format($fechaRecambio, 'd/m/Y');
                        break;
                    default:
                        $resul[$desc] = $_POST[$desc];
                }
            } else {
                 $resul[$desc] = '';
            }
        }
    }
    return $resul;
}

