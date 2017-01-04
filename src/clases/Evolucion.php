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
 * Clase Evolucion
 *
 * Contiene la información sobre la evolución de las molestas del paciente.
 *
 * @author Carlos Cárdenas Negro 
 * @version 0.2
 * @copyright San Miguel Software - Abril/Junio de 2016
 *
 */
class Evolucion extends Base {
 // properties
 // el estado nos dice si la clase está empty o no¡¡¡
 // lo que servirá después para saber si sale a pantalla o no..¡¡
 // vacía: $_estado = false, llena: $_estado = true
 public $_estado = false; 
 
 // etiquetas para los radio... ojo se lo tengo que pasar al padre..?
 protected $_datos = array 
 (
  "title" => "evolucion",
  "desc" => "¿Desde cuando tiene las molestias?",
  "campos" => array
   (
    "fecha_precisa" => array ("label" => "Fecha precisa del comienzo de los síntomas (si la recuerda)", "type" => "date", "value" => "", "id" => "fec_pre"),
    "tiempo_evolucion" => array ("label" => "Tiempo de evolución aproximado desde el comienzo de los síntomas:", "type" => "etiqueta", "value" => "", "id" => "tie_evo"),
    "temporalidad" => array ("label" => "Evolución", "type" => "radio", "value" => "", "id" => "", "valores" => array (
                "aguda" => "aguda <i>(menos de 3 meses)</i>",
                "subaguda" => "subaguda <i>(de 3 á 6 meses)</i>",
                "cronica" => "crónica <i>(más de 6 meses)</i>",
                "larga" => "larga evolucion <i>(más de 1 año)</i>",
                "siempre" => "no lo recuerda <i>(desde siempre)</i>"),
            ),
    "desde_otra" => array ("label" => "Si no puede precisarse mejor, describirlo:", "type" => "textarea",  "value" => "", "id" => "des_otr", "rows" => 2)
  )
 );   
 /**
 * metodos
 */
 
 /**
 * Contructor
 *
 * @param array $param parámetros pasados (since v. 5.6)
 *
 */
 function __construct (...$param) {
  // valores por defecto
  $this->_datos['campos']["fecha_precisa"]['value'] = "";
  //$this->_datos['campos']["tiempo_evolucion"]['value'] = "";
  $this->_datos['campos']["temporalidad"]['value'] = "";
  $this->_datos['campos']["desde_otra"]['value'] = "";
  // se pasaron valores
  switch (count($param)) {
   case 0:
    // already set...
    break;
   case 1:
    $this->_datos['campos']["fecha_precisa"]['value'] = parent::convierteFecha($param[0], 'local');
    break;
   case 2:
    $this->_datos['campos']["fecha_precisa"]['value'] = parent::convierteFecha($param[0], 'local');
    $this->_datos['campos']["temporalidad"]['value'] = $param[1];
    break;
   case 3:
    $this->_datos['campos']["fecha_precisa"]['value'] = parent::convierteFecha($param[0], 'local');
    $this->_datos['campos']["temporalidad"]['value'] = $param[1];
    $this->_datos['campos']["desde_otra"]['value'] = $param[2];
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
            // ojo la fecha puede venir en cristiano/pagano
            // tengo que guardarla siempre en cristiano
            if ($propertyName === 'fecha_precisa') { 
                parent::convierteFecha($propertyValue, 'local'); 
            } 
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

