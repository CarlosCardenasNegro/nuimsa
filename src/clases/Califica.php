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
 * Clase Califica.
 *
 * Contiene la información sobre las características de las molestias que aqueja el paciente.
 *
 */
class Califica extends Base {
 // properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 
 
 protected $_datos = array (
  'title' => "califica_como",
  'desc' => "¿Calificaría el/los síntomas como?",
  'campos' => array
  (
   "intensidad" => array ("label" => "Califica como", "type" => "radio", "value" => "", "id" => "", "valores" => array (
            "vago" => "Vagos",
            "leve" => "Leves",
            "leve_moderado" => "Leves a moderados",
            "moderado" => "Moderados",
            "moderado_severo" => "Moderados a severos",
            "severo" => "Severos",
            "severo_muy_sevedro" => "Severos a muy severos",
            "muy_severo" => "Muy severos",
            "muy_severo_invalidante" => "Muy severos a invalidantes",
            "invalidante" => "Invalidantes")
        )
    )
 );
 // methods
 // contructor
 function __construct (...$param) {
  // valores por defecto
  $this->_datos['campos']["intensidad"]['value'] = "";
  if (func_num_args() == 1) {
    $this->_datos['campos']["intensidad"]['value'] = $param[0];
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
