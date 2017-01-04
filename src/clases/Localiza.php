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
 * Define la clase Localiza.
 *
 * Contiene la información referente a la localización de las molestias.
 *
 */
class Localiza extends Base {
 //properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 
 
 protected $_datos = array (
  "title" => "localiza", 
  "desc" => "Localización", 
  "campos" => array
  (
   "loc_anatomica" => array ("label" => "Localización anatómica precisa o aproximada (no olvidar la LATERALIDAD¡¡)", "type" => "textarea", "value" => "", "id" => "loc_ana", "rows" => 3),
   "loc_region" => array ("label" => "Región aproximada (por ejemplo, borde medial, tercio medio, etc)", "type" => "textarea", "value" => "", "id" => "loc_reg", "rows" => 3)
  )
 ); 
 // methods
 // contructor
 function __construct (...$param) {
  // valores por defecto
  $this->_datos['campos']["loc_anatomica"]['value'] = "";
  $this->_datos['campos']["loc_region"]['value'] = "";
  // se pasaron valores
  switch (func_num_args()) {
   case 0:
    // already set...
    break;
   case 1:
    $this->_datos['campos']["loc_anatomica"]['value'] = $param[0];
    break;
   case 2:
    $this->_datos['campos']["loc_anatomica"]['value'] = $param[0];
    $this->_datos['campos']["loc_region"]['value'] = $param[1];
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

   
      
 