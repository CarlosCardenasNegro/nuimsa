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
 * Clase Incidencias.
 *
 * Define las incidencias relativas a la exploración que el técnico quiere
 * dejar referidas al poder tener importancia a la hora de interpretar los hallazgos.
 *
 */
class Incidencias extends Base {
 // properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 
 
 protected $_datos = array (
  "title" => "incidencias", 
  "desc" => "Incidencias de interés ocurridas durante el transcurso de la prueba", 
  "campos" => array
  (
   "observaciones" => array ("label" => "Incidencias de la prueba", "type" => "textarea", "value" => "", "id" => "obs")
  )
 );
 // methods
 // contructor
 function __construct (...$param) {
  switch (func_num_args()) {
   case 0:
    // valores por defecto
    $this->_datos['campos']["observaciones"]['value'] = "";
    break;
   case 1:
    $this->_datos['campos']["observaciones"]['value'] = $param[0];
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
