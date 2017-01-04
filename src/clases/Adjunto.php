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
 * Clase Adjunto.
 *
 * Es posible añadir un documento adjunto (imagen, pdf, etc,...) que pudiera ser de interés para la
 * interpretación de las imágenes del paciente.
 *
 * El campo nombre_imagen_hidden contendrá una cadena con todos los nombres de archivo añadidos que
 * podrá ir creciendo secesivamente.
 *
 */
class Adjunto extends Base {
 // properties
 protected $_datos = array (
  "title" => "adjunto", 
  "desc" => "Aporta documento/imagen a los datos clínicos", 
  "campos" => array
  (
   "nombre_archivo" => array ("label" => "Seleccione un archivo/imagen para subir", "type" => "file", "value" => "", "id" => "nom_arc"),
   "nombre_archivo_hidden" => array ("label" => "nah", "type" => "hidden", "value" => "", "id" => "nom_arc_hid"),   
   )
  );  
 // methods
 // contructor
 function __construct (...$param) {
  switch (func_num_args()) {
   case 0:
    // valores por defecto
    $this->_datos['campos']["nombre_archivo"]['value'] = "";
    $this->_datos['campos']["nombre_archivo_hidden"]['value'] = "";
    break;
   case 1:
    $this->_datos['campos']["nombre_archivo"]['value'] = $param[0];
    break;
   case 2:
    $this->_datos['campos']["nombre_archivo"]['value'] = $param[0];
    $this->_datos['campos']["nombre_archivo_hidden"]['value'] = $param[1];
    break;
   }
   parent::__construct($this->_datos);  
 }

 // getters 
 function __get($propertyName) {
  return(parent::__get($propertyName));
 }
 // setters
 function __set($propertyName, $propertyValue) {
  return(parent::__set($propertyName, $propertyValue));
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
