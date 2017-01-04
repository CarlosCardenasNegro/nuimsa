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
 * Define la clase Acude_por.
 *
 * Contiene los datos que definen el motivo por el que el paciente acude.
 *
 */
class Acude_por extends Base {
 // properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 

 protected $_datos = array (
 'title' => "acude_por",
 'desc' => "¿Que le pasa? (<i>motivo por el que acude</i>)",
 'campos' => array
 (
  "poliartralgias" => array ("label" => "Poliartralgias (dolores articulares generales)", "type" => "checkbox", "value" => "", "id" => "pol"),
  "dolor" => array ("label" => "Dolor", "type" => "checkbox", "value" => "", "id" => "dol"),
  "molestias" => array ("label" => "Molestias", "type" => "checkbox", "value" => "", "id" => "mol"),
  "dificultad" => array ("label" => "Dificultad/limitación para el movimiento", "type" => "checkbox", "value" => "", "id" => "dif"),
  "sensaciones" => array ("label" => "Cambios tróficos en la extremid. (color/temp.,piel,etc)", "type" => "checkbox", "value" => "", "id" => "sen"),
  "protesis" => array ("label" => "Prótesis dolorosa y/o con molestias", "type" => "checkbox", "value" => "", "id" => "pro"),
  "artrodesis" => array ("label" => "Artrodesis articular o de columna con molestias o inestabilidad", "type" => "checkbox", "value" => "", "id" => "art"),
  "fractura" => array ("label" => "Fractura complicada (no consolidada/infectada, etc.)", "type" => "checkbox", "value" => "", "id" => "fra"),
  "oncologico" => array ("label" => "Paciente oncológico (valor. afectación metastásica ósea)", "type" => "checkbox", "value" => "", "id" => "onc"),
  "otras" => array ("label" => "Otros motivos (describir)", "type" => "textarea", "value" => "", "id" => "otr"),
  "precipitado" => array ("label" => "Movimientos, acciones o posturas que precipitan o agravan el dolor/irradiación de las molestias", "type" => "textarea", "value" => "", "id" => "pre")      
  )
 );
 // methods
 // contructor
 function __construct (...$param) {
  // valores por defecto
  $this->_datos['campos']["poliartralgias"]['value']   = "";
  $this->_datos['campos']["dolor"]['value']            = "";
  $this->_datos['campos']["molestias"]['value']        = "";
  $this->_datos['campos']["dificultad"]['value']       = "";
  $this->_datos['campos']["sensaciones"]['value']      = "";
  $this->_datos['campos']["protesis"]['value']         = "";
  $this->_datos['campos']["artrodesis"]['value']       = "";
  $this->_datos['campos']["fractura"]['value']         = "";
  $this->_datos['campos']["oncologico"]['value']       = "";
  $this->_datos['campos']["otras"]['value']            = "";
  $this->_datos['campos']["precipitado"]['value']      = "";  
  // se pasaron valores  
  switch (func_num_args()) {
   case 0:
    // already set...
    break;
   case 1:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    break;
   case 2:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    break;
   case 3:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    break;
   case 4:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    break;
   case 5:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    break;
   case 6:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    $this->_datos['campos']["protesis"]['value'] = $param[5];
    break;
   case 7:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    $this->_datos['campos']["protesis"]['value'] = $param[5];
    $this->_datos['campos']["fractura"]['value'] = $param[7];
    break;
   case 9:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    $this->_datos['campos']["protesis"]['value'] = $param[5];
    $this->_datos['campos']["fractura"]['value'] = $param[7];
    $this->_datos['campos']["oncologico"]['value'] = $param[8];
    break;
   case 10:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    $this->_datos['campos']["protesis"]['value'] = $param[5];
    $this->_datos['campos']["fractura"]['value'] = $param[7];
    $this->_datos['campos']["oncologico"]['value'] = $param[8];
    $this->_datos['campos']["otras"]['value'] = $param[9];
    break;
   case 11:
    $this->_datos['campos']["poliartralgias"]['value'] = $param[0];
    $this->_datos['campos']["dolor"]['value'] = $param[1];
    $this->_datos['campos']["molestias"]['value'] = $param[2];
    $this->_datos['campos']["dificultad"]['value'] = $param[3];
    $this->_datos['campos']["sensaciones"]['value'] = $param[4];
    $this->_datos['campos']["protesis"]['value'] = $param[5];
    $this->_datos['campos']["fractura"]['value'] = $param[7];
    $this->_datos['campos']["oncologico"]['value'] = $param[8];
    $this->_datos['campos']["otras"]['value'] = $param[9];
    $this->_datos['campos']["precipitado"]['value'] = $param[10];
    break;
   }
   parent::__construct($this->_datos);  
 }

 // getters 
 function __get($propertyName) {
        if ($propertyName === 'estado') {
            return $this->_estado;
        } else {
            return(parent::__get($propertyName));        
        }
    }
 // setters
 function __set($propertyName, $propertyValue) {
        if ($propertyName === 'estado') {            
            $this->_estado = gettype($propertyValue) === 'boolean' ? $propertyValue : false;
        } else {
        return(parent::__set($propertyName, $propertyValue));
        }
    } 
    
 // tools
 function toXML() {
  return(parent::toXML());
 }
 function toDOMNodes() {
  return(parent::toDOMNodes());  
 }
 function toArray() {
  return(parent::toArray());  
 }
 function getModos($campo) {
  return(parent::getModos($campo));
 }
function toSimpleArray() {
  return(parent::toSimpleArray());  
 }
/**
 * Estilos opcionales para la sección
 * pasados como array asociativo, ej.:
 * {'display' => 'none', 'width' => '80%',...}
 * habitualmente usaremos el display que es el 
 * más útil...
 */
 function toHTML($style = null) {
  return(parent::toHTML($style));  
 }
    /**
     * Establece el $_estado en función de 
     * si la clase tiene datos o no.
     * Devuelve el resultado como:
     * - true ... tiene datos ('llena')
     * - false .. no tiene datos ('vacía')
     */
    function estado() {
        return(parent::estado());     
    }    
    
}


