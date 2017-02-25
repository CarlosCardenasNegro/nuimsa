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
 * Clase Solicitud.
 *
 * Contiene los datos aportados en la solicitud que porta el paciente.
 * 
 */ 
class Solicitud extends Base {
    // properties
    // el estado nos dice si la clase está empty o no¡¡¡
    // lo que servirá después para saber si sale a pantalla o no..¡¡
    // vacía: $_estado = false, llena: $_estado = true
    public $_estado = false; 
    
    protected $_datos = array (
        'title' => "solicitud",
        'desc' => "Datos de la Solicitud",
        'campos' => array
        (  
            "orden"     => array ("label" => "Copie sucintamente la información contenida en la solicitud", "type" => "textarea", "value" => "", "id" => "ode"),
            "nombre_archivo" => array ("label" => "Seleccione un documento para añadir a la entrevista", "type" => "file", "value" => "", "id" => "nom_arc"),
            "nombre_archivo_hidden" => array ("label" => "nah", "type" => "hidden", "value" => "", "id" => "nom_arc_hid"),
            "prioridad" => array ("label" => "Prioridad", "type" => "radio", "value" => "ordinaria", "valores" => array ("ordinaria" => "Ordinaria", "preferente" => "Preferente", "urgente" => "Urgente")),       
            "estado"    => array ("label" => "Estado", "type" => "radio", "value" => "ambulante", "valores" => array ("ambulante" => "Policlínica", "ingresado" => "Ingresado")),
            "interes"   => array ("label" => "Caso de interés",  "type" => "checkbox", "value" => "", "id" => "int")
            )
    );

    // methods
    // contructor
    function __construct (...$param) {
        // defaults
        $this->_datos['campos']["orden"]['value'] = "";
        $this->_datos['campos']["nombre_archivo"]['value'] = "";
        $this->_datos['campos']["nombre_archivo_hidden"]['value'] = "";
        $this->_datos['campos']["prioridad"]['value'] = "ordinaria";
        $this->_datos['campos']["estado"]['value'] = "ambulante";
        $this->_datos['campos']["interes"]['value'] = "";
        
        switch (func_num_args()) {
            // se pasaron valores
            case 1:
                $this->_datos['campos']["orden"]['value'] = $param[0];
                break;
            case 2:
                $this->_datos['campos']["orden"]['value'] = $param[0];
                $this->_datos['campos']["nombre_archivo"]['value'] = $param[1];
                break;
            case 3:
                $this->_datos['campos']["orden"]['value'] = $param[0];
                $this->_datos['campos']["nombre_archivo"]['value'] = $param[1];
                $this->_datos['campos']["nombre_archivo_hidden"]['value'] = $param[2];
                break;
            case 4:
                $this->_datos['campos']["orden"]['value'] = $param[0];
                $this->_datos['campos']["nombre_archivo"]['value'] = $param[1];
                $this->_datos['campos']["nombre_archivo_hidden"]['value'] = $param[2];
                $this->_datos['campos']["prioridad"]['value'] = $param[0];
                break;
            case 5:
                $this->_datos['campos']["orden"]['value'] = $param[0];
                $this->_datos['campos']["nombre_archivo"]['value'] = $param[1];
                $this->_datos['campos']["nombre_archivo_hidden"]['value'] = $param[2];
                $this->_datos['campos']["prioridad"]['value'] = $param[0];
                $this->_datos['campos']["estado"]['value'] = $param[1];
                break;
            case 6:
                $this->_datos['campos']["orden"]['value'] = $param[3];
                $this->_datos['campos']["nombre_archivo"]['value'] = $param[4];
                $this->_datos['campos']["nombre_archivo_hidden"]['value'] =  $param[5];
                $this->_datos['campos']["prioridad"]['value'] = $param[0];
                $this->_datos['campos']["estado"]['value'] = $param[1];
                $this->_datos['campos']["interes"]['value'] = $param[2];
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
