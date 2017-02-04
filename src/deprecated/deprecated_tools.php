
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

