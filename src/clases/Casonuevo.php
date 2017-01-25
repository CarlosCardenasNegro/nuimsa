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
 * Define la clase Casonuevo.
 *
 * Formulario para la recogida de un caso nuevo.
 *
 */ 
class Casonuevo extends Base {
    // properties
    // el estado nos dice si la clase está empty o no¡¡¡
    // lo que servirá después para saber si sale a pantalla o no..¡¡
    // vacía: $_estado = false, llena: $_estado = true
    public $_estado = false; 
    
    protected $_datos = array (
    'title' => "demograficos",
        'desc' => "Creacion/edición de un caso nuevo",
        'campos' => array (
            "iniciales" => array ("label" => "Clave del caso", "type" => "text", "value" => "", "id" => "ini", "required" => "required", "autofocus" => "autofocus", "placeholder" => "iniciales"),                    
            "categoria" => array ("label" => "Categoría", "type" => "select", "value" => "", "id" => "cat", "valores" => array()),
            "tag[]" => array ("label" => "Etiqueta (tag)", "type" => "select", "value" => array(), "id" => "tag", "size" => 4, "multiple" => "multiple", "valores" => array()),
            "dia" => array ("label" => "Fecha", "type" => "date", "value" => "", "id" => "dia"),
            "title" => array ("label" => "Título",   "type" => "text",   "value" => "", "id" => "tit"),
            "subtitle" => array ("label" => "Subtítulo",   "type" => "text",   "value" => "", "id" => "sub"),
            "contenido" => array ("label" => "Contenido e información sobre la imagen (normal or html)", "type" => "textarea", "value" => "", "id" => "con"),
            "imagenes" => array ("label" => "Seleccione las imágenes del caso. Una se debe llamar ICONO y será usada para el icono :", "type" => "file", "value" => "", "id" => "img"),
            "names" => array("label" => "", "type" => "hidden", "value" => "", "id" => "nam"))
    );
    
    // methods
    // contructor
    function __construct (...$param) {
        // default values        
        $this->_datos['campos']["iniciales"]['value'] = "";                
        $this->_datos['campos']["categoria"]['value'] = "1";

        $this->_datos['campos']["categoria"]['valores'] = parent::getValores("categoria", array("id", "description"));                
        $this->_datos['campos']["tag[]"]['valores'] = parent::getValores("tags", array("id", "description"));                        
        
        $this->_datos['campos']["dia"]['value'] = date("d/m/Y");
        $this->_datos['campos']["title"]['value'] = "";
        $this->_datos['campos']["subtitle"]['value'] = "";
        $this->_datos['campos']["contenido"]['value'] = "";
        $this->_datos['campos']["imagenes"]['value'] = "";
        $this->_datos['campos']["names"]['value'] = "";
                
        switch (func_num_args()) {
            case 1:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                break;
            case 2:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                break;
            case 3:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][] = $value;
                    }
                }
                break;
            case 4:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][$key] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                break;
            case 5:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][$key] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                $this->_datos['campos']["title"]['value'] = $param[4];
                break;
            case 6:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][$key] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                $this->_datos['campos']["title"]['value'] = $param[4];
                $this->_datos['campos']["subtitle"]['value'] = $param[5];
                break;
            case 7:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                $this->_datos['campos']["title"]['value'] = $param[4];
                $this->_datos['campos']["subtitle"]['value'] = $param[5];
                $this->_datos['campos']["contenido"]['value'] = $param[6];                
                break;                
            case 8:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][$key] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                $this->_datos['campos']["title"]['value'] = $param[4];
                $this->_datos['campos']["subtitle"]['value'] = $param[5];
                $this->_datos['campos']["contenido"]['value'] = $param[6];
                $this->_datos['campos']["imagenes"]['value'] = $param[7];
                break;                
            case 9:
                $this->_datos['campos']["iniciales"]['value'] = $param[0];
                $this->_datos['campos']["categoria"]['value'] = $param[1];
                // caso especial debería pasarse un array de tags
                if((array)$param === $param) {
                    // OJO, si no se pasa un array quedará default (0)
                    // tag contendrá uno o varios valores
                    foreach ($param[2] as $key => $value) {
                        $this->_datos['campos']["tag[]"]['value'][$key] = $value;
                    }
                }
                $this->_datos['campos']["dia"]['value'] = $param[3];
                $this->_datos['campos']["title"]['value'] = $param[4];
                $this->_datos['campos']["subtitle"]['value'] = $param[5];
                $this->_datos['campos']["contenido"]['value'] = $param[6];
                $this->_datos['campos']["imagenes"]['value'] = $param[7];
                $this->_datos['campos']["names"]['value'] = $param[8];
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


?>