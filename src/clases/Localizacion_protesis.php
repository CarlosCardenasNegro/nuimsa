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
 * Clase Localizacion_protesis.
 *
 * Establece la localización de la prótesis.
 *
 * @author Carlos Cárdenas Negro 
 * @version 0.2
 * @copyright San Miguel Software - Abril/Junio de 2016
 *
 */
class Localizacion_protesis extends Base {
 // properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 
 
 protected $_datos = array
  (
   "title" => "loc_protesis",
   "desc" => "Localización de la prótesis que porta el paciente",
   "campos" => array (
    "localizacion" => array (
        "label" =>  "Loc. anatómica", "type" => "radio", "value" => "",  "valores" => array (
            "hombro" => "Hombro(s)",
            "codo" => "Codo(s)",
            "mano" => "Mano o sus articulaciones",
            "cadera" => "Cadera(s)",
            "rodilla" => "Rodilla(s)",
            "tobillo" => "Tobillo(s)",
            "pies" => "Pies o sus articulaciones"
            )
        )
    )
  );
 // methods
 // contructor
 function __construct (...$param) {
  // valores por defecto (por defecto es la derecha)
  $this->_datos['campos']['localizacion']["value"]= ""; 
  // se pasaron valores
  switch (count($param)) {
   case 0:
    // already set...
   break;
   case 1:
  $this->_datos['campos']['localizacion']['value'] = $param[0];
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
    function estado() {
        return(parent::estado());     
    }
    
}
