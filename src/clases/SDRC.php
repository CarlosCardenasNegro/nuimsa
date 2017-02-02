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
 * Define la clase SDRC.
 *
 * Contiene los datos que definen el síndrome de dolor regional complejo.
 *
 */ 
class SDRC extends Base {
    // properties
    // el estado nos dice si la clase está empty o no¡¡¡
    // lo que servirá después para saber si sale a pantalla o no..¡¡
    // vacía: $_estado = false, llena: $_estado = true
    public $_estado = false; 
    
    protected $_datos = array (
        'title' => "SDRC",
        'desc' => "Seleccione los síntomas/signos que presenta o refiere el paciente",
        'campos' => array
            (
            "motivo" => array ("label" => "Desencadenante clínico", "type" => "radio", "value" => "", "id" => "", "valores" => array (
                    "trauma" => "Traumatismo (<i>sobreesfuer., contusión, fractura,...</i>)",
                    "cirugia" => "Intervención quirúrgica",
                    "neuro" => "Accidente neurológico (<i>ACV</i>)",
                    "desconocido" => "No lo conoce (<i>SDRC tipo II</i>)"
                )
            ),
            "tiempo" => array ("label" => "Tiempo de inmoviliz. aprox.", "type" => "radio", "value" => "", "id" => "", 
                "valores" => array 
                (
                    "nada" => "Sin inmovilz.",
                    "corta" => "De 1 á 2 semanas",
                    "media" => "De 2 sem. á 1 mes",
                    "larga" => "Más de 1 mes"
                ),
            ),
            "sdrc_evolucion" => array ("label" => "Tiempo de evolución aprox.", "type" => "radio", "value" => "", "id" => "", 
                "valores" => array 
                (
                    "menos" => "Menos de 1 mes",
                    "corto" => "De 1 á 3 meses",
                    "medio" => "De 3 á 6 meses",
                    "largo" => "De 6 mes. á 1 año",
                    "muy_largo" => "Más de 1 año"
                ),
            ),
            "sdrc_sensaciones" => array ("label" => "Sensaciones que experimenta el paciente", "type" => "etiqueta", "value" => "", "id" => "sensac"),                        
            "sdrc_dolor" => array ("label" => "Dolor contínuo y/o desproporcionado", "type" => "checkbox", "value" => "", "id" => "dol_con"),
            "temperatura" => array ("label" => "Aumento o disminución de la temperatura", "type" => "checkbox", "value" => "", "id" => "tem"),
            "color" => array ("label" => "Cambios en la coloración de la piel <i>-enrojecimiento, palidez,...-</i>", "type" => "checkbox", "value" => "", "id" => "col"),
            "hinchazon" => array ("label" => "Extremidad hinchada", "type" => "checkbox", "value" => "", "id" => "hin"),
            "sudoracion" => array ("label" => "Aumento de la sudoracion", "type" => "checkbox", "value" => "", "id" => "sud"),
            "movimiento" => array ("label" => "Disminución o pérdida del movimiento", "type" => "checkbox", "value" => "", "id" => "mov"),
            "faneras" => array ("label" => "Aumento del vello o crecimiento de las uñas desmedido", "type" => "checkbox", "value" => "", "id" => "fan"),
            "temblor" => array ("label" => "Temblor", "type" => "checkbox", "value" => "", "id" => "tem"),
            "funcion" => array ("label" => "Pérdida de la funcionalidad", "type" => "checkbox", "value" => "", "id" => "fun"),
            "resultado" => array ("label" => "Resultado de la gammagrafía", "type" => "radio", "value" => "", "id" => "", "valores" => array ("si" => "Sugestivo de SDRC", "no" => "No sugestivo de SDRC")                
        ))
    );

    // methods
    // contructor
    function __construct (...$param) {
        // valores por defecto
        $this->_datos['campos']["motivo"]['value']       = "";
        $this->_datos['campos']["tiempo"]['value']       = "";
        $this->_datos['campos']["sdrc_evolucion"]['value']    = "";
        $this->_datos['campos']["sdrc_sensaciones"]['value']  = "";
        $this->_datos['campos']["sdrc_dolor"]['value']        = "";
        $this->_datos['campos']["temperatura"]['value']  = "";
        $this->_datos['campos']["color"]['value']        = "";
        $this->_datos['campos']["hinchazon"]['value']    = "";
        $this->_datos['campos']["sudoracion"]['value']   = "";
        $this->_datos['campos']["movimiento"]['value']   = "";
        $this->_datos['campos']["faneras"]['value']      = "";  
        $this->_datos['campos']["temblor"]['value']      = "";
        $this->_datos['campos']["funcion"]['value']      = "";  
        // se pasaron valores  
        switch (count($param)) {
            case 0:
                // already set...
                break;
            case 1:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                break;
            case 2:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                break;
            case 3:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                break;
            case 4:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                break;
            case 5:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                break;
            case 6:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                break;
            case 7:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                break;
            case 8:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                break;
            case 9:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                $this->_datos['campos']["sudoracion"]['value']   = $param[8];
                break;
            case 10:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                $this->_datos['campos']["sudoracion"]['value']   = $param[8];
                $this->_datos['campos']["movimiento"]['value']   = $param[9];
                break;
            case 11:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                $this->_datos['campos']["sudoracion"]['value']   = $param[8];
                $this->_datos['campos']["movimiento"]['value']   = $param[9];
                $this->_datos['campos']["faneras"]['value']      = $param[10];  
                break;
            case 12:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                $this->_datos['campos']["sudoracion"]['value']   = $param[8];
                $this->_datos['campos']["movimiento"]['value']   = $param[9];
                $this->_datos['campos']["faneras"]['value']      = $param[10];  
                $this->_datos['campos']["temblor"]['value']      = $param[11];
                break;
            case 13:
                $this->_datos['campos']["motivo"]['value']       = $param[0];
                $this->_datos['campos']["tiempo"]['value']       = $param[1];
                $this->_datos['campos']["sdrc_evolucion"]['value']    = $param[2];
                $this->_datos['campos']["sdrc_sensaciones"]['value']  = $param[3];
                $this->_datos['campos']["sdrc_dolor"]['value']        = $param[4];
                $this->_datos['campos']["temperatura"]['value']  = $param[5];
                $this->_datos['campos']["color"]['value']        = $param[6];
                $this->_datos['campos']["hinchazon"]['value']    = $param[7];
                $this->_datos['campos']["sudoracion"]['value']   = $param[8];
                $this->_datos['campos']["movimiento"]['value']   = $param[9];
                $this->_datos['campos']["faneras"]['value']      = $param[10];  
                $this->_datos['campos']["temblor"]['value']      = $param[11];
                $this->_datos['campos']["funcion"]['value']      = $param[12];  
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

