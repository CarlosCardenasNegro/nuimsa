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
namespace nuimsa\clases;

/**
 * Clase Base.
 * 
 * Contiene la clase BASE de las restantes donde se definen los métodos.
 *
 */  
class Base {
 //properties
 /**
  * Contiene todas las propiedades de la clase.
  * @access protected
  * @var array 
  */
 protected $_datos;  
 /**
  * atención, tal vez, sea error, pues el array debería leerse desde
  * un fichero para no crear esta dependencia,...
  * @access private
  * @var array
  */
 private $_clases = array (
    "div" => "w3-margin-top",
    "header" => "w3-container w3-theme-d2 w3-bor w3-pointer",
    "section" => "w3-border w3-theme-border w3-padding w3-theme-l5",
    "groupbox" => "w3-border w3-theme-border w3-round w3-white w3-padding w3-margin-bottom w3-trescuartos",
    "input" => "w3-input",
    "radio" => "w3-radio",
    "checkbox" => "w3-check w3-margin-left",
    "text" => "w3-input w3-mitad",
    "textarea" => "w3-border w3-theme-border",
    "date" => "w3-date",
    "label" => "w3-text-theme",
    "checklabel" => "w3-text-theme w3-padding-large",                 
    "etiqueta" => "w3-theme-d5",
    "select" => "w3-select w3-border",
    "hidden" => "",
    "file" => ""
); 

 //methods
 //construct
 protected function __construct ($array) {
  $this->_datos = $array;
 } 
 /*
 protected function set_datos($array) {
  $this->_datos = $array;
 }
 */
 // getters
 function __get($propertyName) {
  if (array_key_exists($propertyName, $this->_datos)) {
   return $this->_datos[$propertyName];
  } else {
   // puede que hayamos recibido un campo en cuyo caso devolvemos el ARRAY
   // de todas sus propiedades ej.: $test->iniciales.
   // Si queremos recuperar una propiedad específica debemos usar la variedad
   // $test->iniciales['type'], test->iniciales['value'], etc.
   //
   if (array_key_exists($propertyName, $this->_datos['campos'])) {
    return $this->_datos['campos'][$propertyName];
   } else {
    return null;
   }
  } 
 }
 //setters
 function __set($propertyName, $propertyValue) {
  if (array_key_exists($propertyName, $this->_datos)) {
   $this->_campos[$propertyName] = $propertyValue;
  } else {
   // puede que hayamos recibido un campo pero,
   //  SOLO, SOLO, SOLO... podemos modificar el 'value'
   // ej.: $test->iniciales = "RGF"   
   if (array_key_exists($propertyName, $this->_datos['campos'])) {
    $this->_datos['campos'][$propertyName]['value'] = $propertyValue;
   } else {
    return null;
   }
  } 
 }
 //tools
 // to XML
 protected function toXML() {
  $name = $this->_datos['title'];
  $salida = '<?xml version="1.0" encoding="UTF-8" ?>';
  $salida .= "<$name>";
  foreach ($this->_datos['campos'] as $key => $value) {
   //nota: solo proceso los 5 primeros elementos
   $valor = $value['value'];
   $salida .= "<$key>$valor</$key>";
  }
  $salida .= "</$name>";
  return $salida;
 }
 // to DOMNode
 protected function toDOMNodes() {
  $_doc = new \DOMDocument('1.0', 'UTF-8');
  $_doc->loadXML($this->toXML());
  return $_doc->documentElement;
  unset($_doc);
 }
 // to associative array
 protected function toArray() {
  $resul = array();
  foreach ($this->_datos['campos'] as $key => $value) {
   $resul[$key] = $value['value'];
  }
  return $resul;
 }
 // to numeral -simple- array
 protected function toSimpleArray() {
  foreach ($this->_datos['campos'] as $key => $value) {
   $resul[] = $value['value'];
  }
  return $resul;
 }
 // salida HTML
 //protected function toHTML($clases, $iden) {
 protected function toHTML( $style ) {
 
  // (1) Seccion (id)
  echo '<div id=' . $this->comas($this->title) . 
       ' class= ' . $this->comas($this->_clases["div"]);
  echo $style ? ' style= "' . $style . '">' :  '>' ; 
//           ' style= ' . $style . '>';
 
  // (2) Cabecera...
  // la cabecera 'demograficos' siempre muestra la sección visible
  if ($this->title === 'demograficos') {
    echo '<header class=' . $this->comas($this->_clases["header"]) . ' >';
    echo '<h3 class="w3-lato">' . $this->desc . '</h3>';
    echo '</header>';
    echo '<section class=' . $this->comas($this->_clases["section"]) . ' >';
    // la cabecera 'demograficos' siempre es visible
  } else {
    echo '<header class=' . $this->comas($this->_clases["header"]) . ' style="box-shadow: 2px 2px 4px rgba(0,0,0,0.4)">';
    echo '<h3 class="w3-lato w3-pointer">' . $this->desc . '</h3>';
    echo '</header>';
    echo '<section class=' . $this->comas($this->_clases["section"]) . ' style="display:none">';    
  }
  
  // (3) Campos...
  foreach ($this->campos as $desc => $valor) {
   // la etiqueta...
   $label = '<label class=' . $this->comas($this->_clases["label"]) . '>' . $valor['label'] . '</label><br/>';
 
   // la clase...
   $cl = $this->_clases[$valor["type"]];
 
   // el campo... 
   switch ($valor['type']) {
        case 'text':
            $campo =
            '<input  id='   . $this->comas($valor['id']) .
            ' class='       . $this->comas($cl) .
            ' type='        . $this->comas($valor["type"]) .
            ' name='        . $this->comas($desc) .
            ' value='       . $this->comas($valor["value"]);
            // valores opcionales
            if(isset($valor['required'])) {
                $campo .= ' required=' . $this->comas($valor['required']);                
            }            
            if(isset($valor['disabled'])) {
                $campo .= ' disabled=' . $this->comas($valor['disabled']);                
            }            
            if(isset($valor['autofocus'])) {
                $campo .= ' autofocus=' . $this->comas($valor['autofocus']);                
            }
            if(isset($valor['placeholder'])) {
                $campo .= ' placeholder=' . $this->comas($valor['placeholder']);                
            }
            $campo .= ' /><p/>';
           break;

        case 'date':
            if ($valor['type'] == 'date') { $valor['value'] = $this->convierteFecha($valor['value'], 'ISO'); }
            $campo = 
            '<input  id='   . $this->comas($valor['id']) .
            ' class='       . $this->comas($cl) .
            ' type='        . $this->comas($valor["type"]) .
            ' name='        . $this->comas($desc) .
            ' value='       . $this->comas($valor["value"]);
            // valores opcionales
            if(isset($valor['required'])) {
                $campo .= ' required=' . $this->comas($valor['required']);                
            }            
            if(isset($valor['disabled'])) {
                $campo .= ' disabled=' . $this->comas($valor['disabled']);                
            }            
            if(isset($valor['autofocus'])) {
                $campo .= ' autofocus=' . $this->comas($valor['autofocus']);                
            }
            $campo .= ' /><p/>';
            break;

        case 'textarea':
            $campo = 
            '<textarea  id='   . $this->comas($valor['id']) .
            ' class='       . $this->comas($cl) .
            ' name='        . $this->comas($desc) .
            ' value='       . $this->comas($valor["value"]);
            // valores opcionales
            if(isset($valor['required'])) {
                $campo .= ' required=' . $this->comas($valor['required']);
            }
            if(isset($valor['disabled'])) {
                $campo .= ' disabled=' . $this->comas($valor['disabled']);
            }
            if(isset($valor['autofocus'])) {
                $campo .= ' autofocus=' . $this->comas($valor['autofocus']);
            }            
            if(isset($valor['rows'])) {
                $campo .= ' rows=' . $this->comas($valor['rows']);
            }
            if(isset($valor['cols'])) {
                $campo .= ' cols=' . $this->comas($valor['cols']);
            }
            if(isset($valor['placeholder'])) {
                $campo .= ' placeholder=' . $this->comas($valor['placeholder']);                
            }
            if(isset($valor['wrap'])) {
                $campo .= ' wrap=' . $this->comas($valor['wrap']);
            }
            $campo .=  ' >' . $valor["value"] . '</textarea><p/>';
 
            break;
                       
        case 'select':
            $campo  =
            '<select id='   . $this->comas($valor['id']) .
            ' class='       . $this->comas($cl) .
            ' name='        . $this->comas($desc);
            // valores opcionales
            if (isset($valor['size'])) {
                $campo .= ' size=' . $this->comas($valor['size']);
            }
            if(isset($valor['multiple'])) {
                $campo .= ' multiple=' . $this->comas($valor['multiple']);                
            }
            if(isset($valor['required'])) {
                $campo .= ' required=' . $this->comas($valor['required']);                
            }            
            if(isset($valor['disabled'])) {
                $campo .= ' disabled=' . $this->comas($valor['disabled']);                
            }            
            if(isset($valor['autofocus'])) {
                $campo .= ' autofocus=' . $this->comas($valor['autofocus']);                
            }            
            $campo .= '>';

            //'<option value= "none">none</option>';

            foreach ($valor['valores'] as $key => $resul) {
                $match = false;
                if (is_array($resul)) {
                    // $resul contiene todo lo necesario...

                    //OJO, $valor['value'] puede también ser un array
                    // como ocurre en 'tags' al ser un select multiple..!!
                    if ((array)$valor['value'] === $valor['value']) {
                        // debo comparar cada valor de $resul['id']
                        // con los elementos del array
                        if (in_array($resul['id'], $valor['value'])) {
                            $match = true;                            
                        }
                    } else {
                        // comparación normal contra un solo value
                        if ($resul['id'] == $valor['value']) {
                            $match = true;
                        }                        
                    }
                    
                    if ($match) {
                        $campo .= '<option value= ' . $this->comas($resul['id']);
                        if(isset($resul['data-SAP'])) {
                            $campo .= ' data-SAP= ' . $this->comas($resul["SAPid"]);
                        }
                        $campo .= ' selected="selected">' . $resul['description'] . '</option>';
                    } else {
                        $campo .= '<option value= ' . $this->comas($resul['id']);
                        if(isset($resul['data-SAP'])) {
                            $campo .= ' data-SAP= ' . $this->comas($resul["SAPid"]);
                        }
                        $campo .= '>' . $resul['description'] . '</option>';          
                    }
                } else {
                    // es un array indexado
                    // comparo $key
                    //OJO, $valor['value'] puede también ser un array
                    // como ocurre en 'tags' al ser un select multiple..!!
                    if ((array)$valor['value'] === $valor['value']) {
                        $total = implode(',', $valor['value']);                        
                    } else {
                        $total = $valor['value'];
                    }
                    
                    if (strpos($total, (string)$key)) {
                        $campo .=
                         '<option value= ' . $this->comas($key) .
                         ' selected>' . $resul . '</option>';
                    } else {
                        $campo .=
                         '<option value= ' . $this->comas($key) .
                         '>' . $resul . '</option>';                    
                    }
                }
            }
            $campo .= '</select><p/>';    
            break; 

        case 'radio':
            $cont = -1;
            $campo = '';
            // primero el envoltorio
            $campo .= 
            '<div id=' . $this->comas($valor['label']) .
            ' class='  . $this->comas($this->_clases["groupbox"]) .
            ' style="position:relative; margin-top: 4%">' .
            '<p class="w3-theme w3-card-2" style="position: absolute; margin:-4px; top: -14px; border-radius: 3px 3px 0px 0px; padding: 1px 12px">' . $valor['label'] . '</p>';
                          
            foreach ($valor['valores'] as $rad_key => $rad_valor) {
                // creo el id
                $id = $this->getID($rad_key);    
                if (!empty($valor['value']) && $rad_key == $valor['value']) {
                    $campo .=
                    '<input id='    . $this->comas($id) .
                    ' class='       . $this->comas($cl) .
                    ' type='        . $this->comas($valor['type']) .
                    ' name='        . $this->comas($desc) .
                    ' value='       . $this->comas($rad_key) .
                    ' onchange="radioChange($(this));" ' .
                    ' checked="checked" >';
                } else {
                    $campo .=
                    '<input id='    . $this->comas($id) .
                    ' class='       . $this->comas($cl) .
                    ' type='        . $this->comas($valor['type']) .
                    ' name='        . $this->comas($desc) .
                    ' value='       . $this->comas($rad_key) .
                    ' onchange="radioChange($(this));" >';  
                }
                $campo .=
                '<label class='     . $this->comas($this->_clases["checklabel"]) .
                '>' . $rad_valor . '</label><br/>';
            }
            $campo .= '</div>';
            break;
    
        case 'checkbox':
            if ($valor['value'] == 'on') {
                $campo =
                '<input id='    . $this->comas($valor['id']) .
                ' class='       . $this->comas($cl) .
                ' type='        . $this->comas($valor['type']) .
                ' name='        . $this->comas($desc) .
                ' value='       . $this->comas($valor['value']) .
                ' onchange="checkboxChange ($(this));" ' .    
                ' checked >';
            } else {
                $campo = 
                '<input id='    . $this->comas($valor['id']) .
                ' class='       . $this->comas($cl) .
                ' type='        . $this->comas($valor['type']) .
                ' name='        . $this->comas($desc) .
                ' value='       . $this->comas($valor['value']) .
                ' onchange="checkboxChange ($(this));" >';    
            }
            $campo .= 
            '<label class='     . $this->comas($this->_clases["checklabel"]) .
            '>' . $valor ['label']. '</label><br/>';
            break;
    
        case 'hidden':
            if (!empty($valor['value'])) {
                $campo =
                '<input id='    . $this->comas($valor['id']) .
                ' type='        . $this->comas($valor['type']) .
                ' name='        . $this->comas($desc) .
                ' value='       . $this->comas($valor['value']) .'>';

                /**
                 * caso especial de nom_arc_hid
                 */
                if ($desc === 'nombre_archivo_hidden') {
                    $lista = explode(';', $valor['value']);
                    echo '<br/><table class="w3-table-all w3-margin-top" style="width: 60%">';
                    echo '<tr><th class="w3-theme-d2">Documentos ya subidos:</th></tr>';
                    for ($i = 0; $i < count($lista); $i++) {
                        echo '<tr><td>';
                        echo '<a href="' . strtolower($lista[$i]) . ' " target="_blank">' . $lista[$i] . '</a></td></tr>';
                    }
                    echo '</table><br/>';                    
                }
                
            } else {
                // relleno los campos hidden con la hora y, por defecto, el SAP code para la Ósea...
                $campo =
                '<input id='    . $this->comas($valor['id']) .
                ' type='        . $this->comas($valor['type']) .
                ' name='        . $this->comas($desc) .
                ' value='       . $this->comas($valor['value']) .'>';
            }
            break;
    
        case 'file':
            
            //$label = '<label class=' . $this->comas($cl) . '>' . $valor ['label'] . '</label><br/>';
            
            $campo =
            '<input id='        . $this->comas($valor['id']) .
            ' class='           . $this->comas($cl) .
            ' type='            . $this->comas($valor['type']);
            // para el caso clínico se pueden seleccionar
            // varias imágenes... el name será un array..¡¡
            if ($valor['id'] === 'img') {
                $campo .= ' name='  . $this->comas($desc . '[]');
                $campo .= ' value=' . $this->comas($valor['value']) .  ' multiple >';                
            } else {
                $campo .= ' name='  . $this->comas($desc);
                $campo .= ' value=' . $this->comas($valor['value']) .  '>';
            }
            break;            
       }
       //$campo .= '</div>';
       // salida
       if ($valor['type'] == 'hidden' || $valor['type'] == 'radio' || $valor['type'] == 'checkbox') { echo $campo; }
       if ($valor['type'] == 'text' || $valor['type'] == 'textarea' || $valor['type'] == 'select' || $valor['type'] == 'date' || $valor['type'] == 'file') { echo $label; echo $campo; }
       if ($valor['type'] == 'etiqueta') { echo $label; }
    }
    // cierra el segmento
    echo '</section>';
    echo '</div>';
    
} 

    /**
     * Helper functions
     */
    
    /**
     * Añade comillas antes de los campos para
     * la salida HTML
     *
     * @param $var string string a entrecomillar
     * @return string entrecomillada
     */
    protected function comas($var) {
        return '"' . $var . '"';
    }

    /**
     * Convierte entre fecha Local (dd/mm/yyyy) e ISO-Chrome (yyyy-mm-dd)
     * 
     * @param string $fecha Fecha a convertir
     * @param string $modo  Modo de conversión ('local', 'ISO')
     */
    protected function convierteFecha ($fecha, $modo) {
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
     * Formatea un id a partir de la string
     * pasada que vendrá con underscore '_'
     * Se usan los tres primeros caracteres 
     * de cada palabra.
     *
     * @param $valor string
     * @return $temp la string preparada
     */     
    protected function getID($valor) {         
        $parts = explode('_', $valor);    
        $temp = '';
        foreach ($parts as $value) {
            $temp .= substr($value, 0, 3) . '_';
        }
        // antes de salir quito el último _
        return substr($temp, 0, strlen($temp)-1);
     }
    /**
     * Establece el $_estado en función de 
     * si la clase tiene datos o no.
     * Devuelve el resultado como:
     * - true ... tiene datos ('llena')
     * - false .. no tiene datos ('vacía')
     */
    protected function estado() {         
        foreach ($this->_datos['campos'] as $key => $value) {
            // ojo algunas clases tienen valores por defecto
            // y devolverán siempre true,... por ahora lo 
            // dejo así...
            // demograficos siempre devolverá true...¡¡¡
            if (!empty($value['value'])) { 
                $this->_estado = true;
                return true;                 
            }
        }
        // si llegamos aqui es que no hay ningun valor            
        $this->_estado = false;
        return false;
    }
    
    /**
     * Recupero los valores desde una tabla...¡¡
     * no me parece correcto que una clase dependa
     * de una tabla externa,.. pero...???
     * de forma experimental me voy a meter
     * en el ajo este...
     *
     * He añadido la posibilidad o necesidad de pasar el 'id'
     * pues por lo general el 'id' se pasará al campo 'value'
     * y la 'description' al campo 'valores', ya que, las id no
     * tienen porque ser correlativas pues si borramos una no se recupera
     * su id y se saltan valores,...
     *
     * @param string $tabla Nombre de la Tabla donde se encuentran los valores
     * @param array $campos Nombre de(los) campo(s) a recuperar
     */
    protected function getValores($tabla, $campos) {

        $result = array(); 
        $campos_s = implode(',', $campos);
        $sql = "select $campos_s FROM $tabla";
        
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

            $cont = 0;
            foreach ($conn->query($sql, \PDO::FETCH_ASSOC) as $row) {
                for ($i = 0; $i < count($campos); $i++) {
                    $result[$cont][$campos[$i]] = $row[$campos[$i]];
                }
                $cont += 1;
            }
            return $result;
            $conn = null;        
        }
        catch(\PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }        
    }        
}

?>