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
 * Clase Desde_cuando_protesis.
 *
 * Evolución de las molestias que aqueja el paciente
 * referidas a la prótesis.
 * 
 * Nota: añado la propiedad Califica_como para ahorrarme trabajo
 *
 */
class Evolucion_protesis extends Evolucion {
    
    // properties
    // el estado nos dice si la clase está empty o no¡¡¡
    // lo que servirá después para saber si sale a pantalla o no..¡¡
    // vacía: $_estado = false, llena: $_estado = true
    public $_estado = false; 
     
    protected $_lado;
    
    //methods
    //constructor
    function __construct(...$param) {
        // le añado al padre 3 campos propios
        $this->_datos['campos']['no_molestias'] = array ("label" => "No tiene molestias", "type" => "checkbox", "value" => "", "id" => "");
        $this->_datos['campos']['fecha_recambio'] = array ("label" => "Fecha precisa del recambio si lo hubo -solo anotar el último recambio-", "type" => "date", "value" => "", "id" => "");
        $this->_datos['campos']['intensidad'] = array ("label" => "Severidad de los síntomas", "type" => "radio", "value" => "", "id" => "",
                "valores" => array (
                    "vago" => "Vagos",
                    "leve" => "Leves",
                    "leve_moderado" => "Leves a moderados",
                    "moderado" => "Moderados",
                    "moderado_severo" => "Moderados a severos",
                    "severo" => "Severos",
                    "severo_muy_sevedro" => "Severos a muy severos",
                    "muy_severo" => "Muy severos",
                    "muy_severo_invalidante" => "Muy severos a invalidantes",
                    "invalidante" => "Invalidantes"
                )
        );

        // relleno los nuevos campos (por defecto el lado es el derecho)
        $this->_lado = 'derecha'; //valores posibles derecha, izquierda, ambas
        $this->_datos['campos']['no_molestias']['value'] = '';
        $this->_datos['campos']['fecha_recambio']['value'] = '';
        $this->_datos['campos']['intensidad']['value'] = '';
        
        // el primer parámetro pasado será el _lado
        // luego se rellenan los campos restantes
        // del padre y los 3 añadidos
        parent::__construct();                     

        switch (count($param)) {
            case 0:
                // already set...
                break;
            // mando al padre los parámetros individualmente 
            // pues sino se construyen dos arrays...
            case 1:
                $this->_lado = $param[0];
                break;  
            case 2:
                $this->_lado = $param[0];
                parent::__construct($param[1]);
                break;  
            case 3:
                $this->_lado = $param[0];
                // nota el parametro 2 es una etiqueta no se rellena
                parent::__construct($param[1],$param[2]);
                break;  
            case 4:
                $this->_lado = $param[0];
                parent::__construct($param[1],$param[2],$param[3]);
                break;
            case 5:
                $this->_lado = $param[0];
                parent::__construct($param[1],$param[2],$param[3]);
                // los propios
                $this->_datos['campos']['no_molestias']['value'] = $param[4];
                break;
            case 6:
                $this->_lado = $param[0];
                parent::__construct($param[1],$param[2],$param[3]);
                // los propios
                $this->_datos['campos']['no_molestias']['value'] = $param[4];
                $this->_datos['campos']['fecha_recambio']['value'] = parent::convierteFecha($param[1], 'local');
                break;
            case 7:
                $this->_lado = $param[0];
                parent::__construct($param[1],$param[2],$param[3]);
                // los propios
                $this->_datos['campos']['no_molestias']['value'] = $param[4];
                $this->_datos['campos']['fecha_recambio']['value'] = parent::convierteFecha($param[1], 'local');
                $this->_datos['campos']['intensidad']['value'] = $param[6];
                break;
        }

        // cambio las id para la lateralidad
        $this->_datos['campos']['fecha_precisa']['id'] = "fec_pre_" . substr($this->_lado, 0, 1);
        $this->_datos['campos']['temporalidad']['id'] = substr($this->_lado, 0, 1); //OJO, si la id esta vacía es Desde_cuando, pero si no lleva la lateralidad a añadir...
        $this->_datos['campos']['desde_otra']['id'] = "des_otr_" . substr($this->_lado, 0, 1);
        $this->_datos['campos']['no_molestias']['id'] = "no_mol_" . substr($this->_lado, 0, 1);
        $this->_datos['campos']['fecha_recambio']['id'] = "fec_rec_" . substr($this->_lado, 0, 1);
        $this->_datos['campos']['intensidad']['id'] = "intensidad_" . substr($this->_lado, 0, 1);

        // modifico la descripcion general
        $this->_datos['desc'] = 'Información clínica relativa a la prótesis ' . $this->_lado;

        // modifico la descripcion de la fecha_precisa
        $this->_datos['campos']['fecha_precisa']['label'] = "Fecha precisa de la implantación de la prótesis si la recuerda -puede redondear el día a 15-";

        // modifica la id para reflejar protesis y lateralidad
        $this->_datos['title'] = $this->_datos['title'] . '_protesis_' . substr($this->_lado, 0, 3);
        // modifico las key para reflejar la lateralidad
        foreach ($this->_datos['campos'] as $key => $valor) {
            $newkey = $key . '_' . substr($this->_lado,0,1);
            $this->_datos['campos'][$newkey]  = $this->_datos['campos'][$key];
            unset($this->_datos['campos'][$key]);
        }
        //var_dump($this->_datos); exit;        
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
    
    // tools que actuan sobre el array 'campos'
    function toXML() {
        return(parent::toXML());
    }
    function toDOMNodes() {
        return(parent::toDOMNodes());  
    }
    function toArray() {
        return(parent::toArray());  
    }
    function toSimpleArray() {
        return(parent::toSimpleArray());  
    }
    function getModos($campo) {
        return(parent::getModos($campo));
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
