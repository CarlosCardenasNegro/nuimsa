<?php
/**
 * Nuclear Medicine Toolkit(tm) (http://www.nuimsa.es)
 * San Miguel Software, Sl. - Marzo/Mayo de 2016 (http://www.sanmiguelsoftware.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.TXT (https://www.tldrlegal.com/l/mit)
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  San Miguel Software - Marzo/Mayo de 2016 (http://www.sanmiguelsoftware.com)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * @author    Carlos Cárdenas Negro
 * @version   1.0
 * @link      (http://www.nuimsa.es)
 */

/**
 * Utilidad para la lectura de los archivos de información
 * xml.
 */
 
if (!isset($_POST['ci'])) {   
    echo "Error";
    exit;    
}

// cargo el documento de información
$filename = htmlentities($_POST['ci']);
$doc = new DOMDocument("1.0", "utf-8");
$doc->load('ci/' . $filename);

// es un CI..?

if ($doc->documentElement->nodeName != 'ci') {
    echo "Error.El documento seleccionado no contiene un Consentimiento Informado o es inválido.";    
}

$data = array();
$resul = '';

$xpath = new DOMXpath($doc);

// titulo
$ruta = "/ci/title";
$titulo = $xpath->query($ruta);
$data['titulo'] = $titulo->item(0)->nodeValue;

// subtitulo
$ruta = "/ci/shortdesc";
$subtitulo = $xpath->query($ruta);
$data['subtitulo'] = $subtitulo->item(0)->nodeValue;

// table de contenidos
$ruta = "/ci/topic/title";
$toc = $xpath->query($ruta);

$data['toc'] = array ();
if ($toc->length > 0) {
    foreach ($toc as $key => $value) {
        $data['toc'][] = $value->nodeValue;        
    }
}

// topics
$ruta = "/ci/topic";
$res = $xpath->query($ruta);

// itero los topics
foreach ($res as $key => $value) {
    $resul = '';
    fetchChilds($value);
    $data['topics'][$data['toc'][$key]] = $resul; 
}

// salida a pantalla

?>
<style>
#informacion { 
    position: relative; 
    top: 5%; 
    width: 98%; 
    margin-left: 1%; 
    padding: 5px; 
    height: 93%;
    display:none
}
#informacion header {
    position: absolute;
    top: 2%;
    width: 99%;
    margin-left: 0,5%
}

#informacion button {
    position: absolute;
    bottom: 1%;
    width: 98%
}
    
#contenido { 
    position: relative; 
    top: 26%; 
    width: 100%;
    height: 73%;
    margin: 0px;
}

#toc { 
    position: absolute; 
    top: 10%; 
    width: 25%;
    height: 73%;
    margin: 0px;
    overflow: auto;
}
#toc table {
    margin: auto;
}
#texto {
    position: absolute; 
    top: 5%;
    left: 26%;    
    width: 73%;
    height: 85%;
    margin: 0px;
    overflow: auto;
}

</style>
<div class="popup">
    <div id="informacion" class="w3-container w3-card-4 w3-theme-l5 w3-text-theme-l1">

        <header class="w3-theme">
            <div class="w3-container w3-center">
                <h1 class="w3-jumbo w3-lato"><?= $data['titulo'] ?></h1>
                <h2 class="w3-lato"><?= $data['subtitulo'] ?></h2>
            </div>
        </header>

        <div id="contenido" class="w3-container w3-theme-l5">
            
            <div id="toc" class="w3-col m3 w3-tiny">
                <table class="w3-table w3-striped w3-hoverable w3-theme-l4 w3-card-2 w3-margin-top">
                    <thead>
                        <tr class="w3-theme-d3"><td class="w3-center" colspan="2">Tabla de contenidos</td></tr>
                        <tr>
                            <td class="w3-center w3-theme-l1">Sección</td>
                            <td class ="w3-center w3-theme-l2">Contenido</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['toc'] as $key => $value): ?>
                            <tr>
                                <td class="w3-center"><?= $key + 1 ?></td>
                                <td><a href="#<?= strtolower($value) ?>"><?= $value ?></a></td>
                            </tr>                        
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="texto" class="w3-container w3-medium">
                <?php foreach ($data['topics'] as $key => $value) { echo $value; } ?>
                </div>            
        </div>    

        <button class="w3-btn w3-wide w3-theme-d5 w3-hover-theme w3-hover-theme:hover" id="cancel" onclick="cargaPag('inicio.php')">Volver al inicio</button>            
        <br/>
        
    </div>
</div>

<script>
$ (function() {
   $( '#informacion' ).fadeTo('slow',.9);
  
    $( 'a[href^="#"]' ).on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 74
            }, 1000);
        }
    });    
});
</script>    
 
<?php    

exit;

function fetchChilds( $nodo ) {
    global $resul;
    
    if (get_class($nodo) === 'DOMNodeList') {
        // si es un NodeList desgloso los items
        for ($i = 0; $i < $nodo->length; $i++) {
            fetchChilds($nodo->item($i));
        }        
    }
    
    if  (get_class($nodo) === 'DOMElement') {
        // es un DOMElement a por el
        $resul .= setTag('open', $nodo);

        if ($nodo->hasChildNodes()) {  //and $nodo->childNodes->length > 1 ) {
            fetchChilds($nodo->childNodes);
        }
        
        $resul .= setTag ('end', $nodo);         
    }
    
    if (get_class($nodo) === 'DOMText') {
        // hay un excepción el 'fig'
        $padre = $nodo->parentNode->parentNode;
        if ($padre->nodeName != 'fig') {
            // para evitar los TextNodes vacíos
            if (trim($nodo->nodeValue) !== '') {
                $resul .= ($nodo->nodeValue); 
            }
        }
    }    
    return $resul;
}

/**
 * Convierte los tag del documento xml a
 * el apropiado tag HTML.
 *
 * A la función se accede en tres ocasiones
 * una 'open' para 'abrir', una segunda
 * 'tag' para añadir el contenido y una tercera
 * 'end' para cerrarlo.
 * En ciertos casos solo la acción 'open' será
 * procesada el resto devuelve 'res' vacía
 *
 * @param string $tipo tipo de acción 'open', 'tag' y 'end'.
 * @param DOMElement $nodo Elemento a procesar.
 * @return string $res contenido ya convertido
 */
function setTag( $tipo, $nodo ) {
    
    $res = '';
    
    switch ($nodo->nodeName) {
        
        // Nodos especiales y/o con contenido específico

        case 'shortdesc':
            if ($tipo === 'open') { $res = '<p class="w3-xlarge">'; }
            if ($tipo === 'tag') { $res = trim($nodo->nodeValue); }
            if ($tipo === 'end') { $res = '</p>'; }
            break;
        case 'title':
            // lo tratamos independientemente si 
            // el padre es un topic (ponemos letra grande)
            // el resto lo ignoramos...
            switch ($nodo->parentNode->nodeName) {
                case 'topic':
                    if ($tipo === 'open') { $res = '<p class="w3-lato w3-xxxlarge">'; }           
                    if ($tipo === 'tag') { $res = trim($nodo->nodeValue); }
                    if ($tipo === 'end') { $res = '</p>'; }
                    break;
                case 'section':
                    $cont = $nodo->nodeValue;
                    if ($cont !== 'Advertencia' and 
                        $cont !== 'Información' and
                        $cont !== 'Interacciones') {
                        if ($tipo === 'open') { $res = '<p class="w3-xlarge">'; }
                        if ($tipo === 'end') { $res = '</p>'; }
                    } else {
                        if ($tipo === 'open') { $res = '<b>'; }
                        if ($tipo === 'end') { $res = '</b>'; }
                    }
                    break;
            }
            break;
        case 'p':
            if ($tipo === 'open') { $res = '<p>'; }
            if ($tipo === 'tag') { $res = trim($nodo->nodeValue); }
            if ($tipo === 'end') { $res = '</p>'; }
            break;
        case 'img':
            // la imagen se compondrá como un card
            // caso especial tiene atributos y no cierre
            if ($tipo === 'open') {
                $res = '<table style="border-spacing: 0px; width:25%; margin: 10px; float:left">';
                $res .= '<tr><td><img';
                $href = 'ci/' . $nodo->getAttribute('href');
                $width = $nodo->getAttribute('width');
                $height = $nodo->getAttribute('height');
                if ($href) { $res .= " src='$href' "; }
                if ($width) { $res .= "width='$width' "; }
                if ($height) { $res .= "height='$height' "; }
                $res .= ' style="margin-right:2%"/></td></tr>';
                // ahora el título
                $tit = $nodo->previousSibling;
                while ($tit->nodeType != '1') { $tit = $tit->previousSibling; }
                $tit = trim($tit->nodeValue);
                $res .= '<tr class="w3-center w3-tiny"><td style="padding: 2px">';
                $res .= $tit . '</td></tr></table>';
            }
            if ($tipo === 'tag') { $res = ''; }
            if ($tipo === 'end') { $res = ''; }
            break;
        case 'section':
            if ($tipo === 'open') {
                $hijos = $nodo->childNodes;
                for ($i = 0; $i < $hijos->length; $i++) {
                    if ($hijos->item($i)->nodeName === 'title') {
                        $valor = trim($hijos->item($i)->nodeValue);
                        switch ($valor) {
                            case 'Información':
                                $res .= '<div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue w3-padding" style="width: 90%; margin: auto">';
                                break;
                            case 'Advertencia':
                                $res .= '<div class="w3-panel w3-pale-red w3-leftbar w3-border-red w3-padding" style="width: 90%; margin: auto">';
                                break;
                            case 'Interacciones':
                                $res .= '<div class="w3-panel w3-pale-green w3-leftbar w3-border-green w3-padding" style="width: 90%; margin: auto">';
                                break;
                            default:    
                                $res = '<div>';
                                break;
                        }
                    }
                }
            }
            if ($tipo === 'end') { $res = '</div>'; }                
            break;
            
        // Nodos convertidos pero sin contenido (no 'tag')

        case 'topic':
            $atr = $nodo->getAttribute('id');
            if ($tipo === 'open') { $res = "<section id='$atr'>"; }
            if ($tipo === 'tag') { $res = ''; }
            if ($tipo === 'end') { $res = '</section>'; }
            break;            
        case 'body':
            if ($tipo === 'open') { $res = "<div>"; }
            if ($tipo === 'tag') { $res = ''; }
            if ($tipo === 'end') { $res = '</div>'; }
            break;            
        case 'fig':
            // caso no hago nada pues lo hago todo en img
            break;
            
        // nodos con tag estándar

        case 'table':
            if ($tipo === 'open') { $res = '<table class="w3-table-all" style="width: 80%; margin: auto">'; }
            if ($tipo === 'tag') { $res = ''; }
            if ($tipo === 'end') { $res = '</table>'; }
            break;
        case 'caption':
            if ($tipo === 'open') { $res = '<caption class="w3-xlarge">'; }
            if ($tipo === 'tag') { $res = trim($nodo->nodeValue); }
            if ($tipo === 'end') { $res = '</caption>'; }
            break;
        case 'thead':
            // no break
        case 'tr':
        case 'th':
        case 'tbody':
        case 'td':
        case 'ol':
        case 'ul':
        case 'dl':
        case 'dd':
        case 'dt':
        case 'li':
            if ($tipo === 'open') { $res = '<' . $nodo->nodeName . '>'; }
            if ($tipo === 'tag') { $res = $nodo->nodeValue; }
            if ($tipo === 'end') { $res = '</' . $nodo->nodeName . '>'; }
            break;
            
        // Nodos sin contenido (br, b, i,...)

        default:
            if ($tipo === 'open') { $res = '<' . $nodo->nodeName . '>'; }
            if ($tipo === 'end') { $res = '</' . $nodo->nodeName . '>'; }
            break;
    }
    return $res;
}

?>