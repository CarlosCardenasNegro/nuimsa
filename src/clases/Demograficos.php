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

//if (!defined('DEMOGRAFICOS')) { define('DEMOGRAFICOS', 'Demograficos class defined'); }
//if (!defined('BASE')) { require_once 'base.php'; }

/**
 * Clase Demograficos.
 * 
 * Contiene los datos básicos del paciente que
 * permiten su posterior identificación interna.
 *
 * OJO, se mantiene el anonimato ya que no hay
 * relación entre estos datos y un paciente real
 */ 
class Demograficos extends Base {
    // properties
    // el estado nos dice si la clase está empty o no¡¡¡
    // lo que servirá después para saber si sale a pantalla o no..¡¡
    // vacía: $_estado = false, llena: $_estado = true
    // NOTA: el estado lo pongo YO..¡¡¡
    // Excepto en esta clase en que se pone automático cada
    // vez que se rellenan las iniciales..¡¡
    public $_estado = false; 
    
    protected $_datos = array (
        'title' => "demograficos",
        'desc' => "Datos del paciente y prueba",
        'campos' => array (
            "iniciales"   => array ("label" => "Iniciales", "type" => "text",   "value" => "", "id" => "ini", "required" => "required", "autofocus" => "autofocus"),
            "fecha"       => array ("label" => "Fecha", "type" => "date",   "value" => "", "id" => "fec", "required" => "required"),
            "hora"        => array ("label" => "Hora", "type" => "hidden", "value" => "", "id" => "hor"),
            "exploracion" => array ("label" => "Exploracion", "type" => "select", "value" => "", "id" => "exp", "valores" => array (
                "coloides" => array ("description" => "Coloides", "id" => "GAMMAGRAFIA DE MEDULA OSEA", "SAPid" => "992729", "CI" => "", "image" => ""),
                "esfuerzo" => array ("description" => "Prueba de esfuerzo", "id" => "PERFUSION MIOCARDICA EN ESFUERZO", "SAPid" => "992706", "CI" => "CI_PerfusionMiocardica.pdf", "image" => "myocardial.png"),
                "centinela" => array ("description" => "Ganglio centinela", "id" => "LINFOGAMMAGRAFIA PARA DETECCION DE G. CENTINELA", "SAPid" => "999999", "CI" => "", "image" => ""),
                "hematies" => array ("description" => "Hematies marcados", "id" => "GAMMAGRAFIA DE POOL VASCULAR HEPATICO", "SAPid" => "992710", "CI" => "CI_HematiesMarcados.pdf", "image" => "generic.png"),
                "leucocitos" => array ("description" => "Leucocitos marcados", "id" => "LEUCOCITOS MARCADOS PARA INFECCION OSEA", "SAPid" => "998683", "CI" => "CI_Leucocitos.pdf", "image" => "leucocyte.png"),
                "meckel" => array ("description" => "Divertículo de Meckel", "id" => "DETECCION DE DIVERTICULO DE MECKEL", "SAPid" => "992704", "CI" => "CI_Generico.pdf", "image" => "generic.png"),
                "osea" => array ("description" => "Osea, gammagrafía", "id" => "GAMMAGRAFIA OSEA","SAPid" => "992740", "CI" => "CI_G_Osea.pdf", "image" => "bone.png"),
                "paratiroides" => array ("description" => "Paratiroides, gammagrafía", "id" => "GAMMAGRAFIA DE PARATIROIDES", "SAPid" => "992793", "CI" => "CI_Generico.pdf", "image" => "parathyroid.png"),
                "perfu/venti" => array ("description" => "Perfusion/ventilacion pulmonar", "id" => "GAMMAGRAFIA PULMONAR DE PERFUSION/VENTILACION", "SAPid" => "992734", "CI" => "CI_VentiPerfu.pdf", "image" => "lungs.png"),
                "rastreo_galio" => array ("description" => "Rastreo con galio-67", "id" => "GAMMAGRAFIA CON GALIO-67", "SAPid" => "992742", "CI" => "CI_Galio.pdf", "image" => "gallium.png"),
                "rastreo_MIBG" => array ("description" => "Rastreo con I-123-MIBG", "id" => "RASTREO CORPORAL CON I-123-MIBG", "SAPid" => "995645", "CI" => "CI_generico.pdf", "image" => ""),
                "renal" => array ("description" => "Renal, gammagrafía", "id" => "GAMMAGRAFIA RENAL", "SAPid" => "992717", "CI" => "CI_Renal.pdf", "image" => "renal.png"),
                "renograma" => array ("description" => "Renograma", "id" => "RENOGRAMA ISOTOPICO", "SAPid" => "992731", "CI" => "CI_Renograma.pdf", "image" => "renography.png"),
                "reposo" => array ("description" => "Prueba de reposo", "id" => "PERFUSION MIOCARDICA EN REPOSO", "SAPid" => "992706", "CI" => "", "image" => ""),
                "salivales" => array ("description" => "Salivales, gammagrafía", "id" => "GAMMAGRAFIA DE GLANDULAS SALIVALES", "SAPid" => "992700", "CI" => "CI_Generico.pdf", "image" => "salivary.png"),
                "sangramiento" => array ("description" => "Detección de sangramiento", "id" => "GAMMAGRAFIA DE SANGRAMIENTO", "SAPid" => "992705", "CI" => "CI_Generico.pdf", "image" => "generic.png"),
                "spect_cerebral" => array ("description" => "SPECT cerebral", "id" => "SPECT DE PERFUSION CEREBRAL", "SAPid" => "992750", "CI" => "CI_SpectCerebral.pdf", "image" => "brain_spect.png"),
                "tiroidea" => array ("description" => "Tiroidea, gammagrafía", "id" => "GAMMAGRAFIA TIROIDEA", "SAPid" => "992716", "CI" => "CI_Tiroides.pdf", "image" => "thyroid.png"),
                "tto_iodo" => array ("description" => "Tratamiento con iodo-131", "id" => "TRAT. CON IODO-131 EN HIPERTIROIDISMO", "SAPid" => "992792", "CI" => "CI_TtoHipertiroidismo.pdf", "image" => "generic.png"),
                "vaciamiento" => array ("description" => "Vaciamiento gástrico líquidos", "id" => "GAMMAGRAFIA DE VACIAMIENTO GASTRICO", "SAPid" => "992703", "CI" => "CI_Generico.pdf", "image" => "generic.png"),
                "ioflupano" => array ("description" => "Transportadores de dopamina (DATSCAN)", "id" => "GAMMAGRAFIA DE TRANSPORTADORES DE DOPAMINA", "SAPid" => "992799", "CI" => "CI_Generico.pdf", "image" => "dopamine.png"),
                "otra" => array ("description" => "Otra", "id" => "OTRA", "SAPid" => "999999", "CI" => "CI_Generico.pdf", "image" => "generic.png")
                    )),
            "codigo_SAP"  => array ("label" => "Código SAP",  "type" => "hidden", "value" => "", "id" => "cod_SAP"),
            "usuario"     => array ("label" => "Usuario",     "type" => "hidden", "value" => "", "id" => "usu")
        )
    ); 
    
    // methods
    // contructor 
    function __construct (...$param) {
    
        // valores por defecto
        $this->_datos['campos']["iniciales"]['value'] = "";
        $this->_datos['campos']["fecha"]['value'] = date("d/m/Y");
        $this->_datos['campos']["hora"]['value'] = date("H:i:s");
        $this->_datos['campos']["exploracion"]['value'] = "GAMMAGRAFIA OSEA";
        $this->_datos['campos']["codigo_SAP"]['value'] = "992740";
        $this->_datos['campos']["usuario"]['value'] = "";
        // se pasaron valores
        switch (count($param)) {
            case 0:
                // already set...
                break;
            case 1:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_estado = true;
                break;
            case 2:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_datos['campos']["fecha"]['value'] = parent::convierteFecha($param[1], 'local');
                $this->_estado = true;
                break;
            case 3:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_datos['campos']["fecha"]['value'] = parent::convierteFecha($param[1], 'local');
                //$this->_datos['campos']["hora"]['value'] = $param[2]; // Nota: la hora siempre se pasará empty '' y no la voy a cambiar
                $this->_estado = true;
                break;
            case 4:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_datos['campos']["fecha"]['value'] = parent::convierteFecha($param[1], 'local');
                //$this->_datos['campos']["hora"]['value'] = $param[2]; // Nota: la hora siempre se pasará empty '' y no la voy a cambiar
                $this->_datos['campos']["exploracion"]['value'] = $param[3];
                $this->_estado = true;
                break;
            case 5:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_datos['campos']["fecha"]['value'] = parent::convierteFecha($param[1], 'local');
                //$this->_datos['campos']["hora"]['value'] = $param[2]; // Nota: la hora siempre se pasará empty '' y no la voy a cambiar
                $this->_datos['campos']["exploracion"]['value'] = $param[3];
                $this->_datos['campos']["codigo_SAP"]['value'] = $param[4];
                $this->_estado = true;
                break;
            case 6:
                $this->_datos['campos']["iniciales"]['value'] = strtoupper($param[0]);
                $this->_datos['campos']["fecha"]['value'] = parent::convierteFecha($param[1], 'local');
                //$this->_datos['campos']["hora"]['value'] = $param[2]; // Nota: la hora siempre se pasará empty '' y no la voy a cambiar
                $this->_datos['campos']["exploracion"]['value'] = $param[3];
                $this->_datos['campos']["codigo_SAP"]['value'] = $param[4];
                $this->_datos['campos']["usuario"]['value'] = $param[5];
                $this->_estado = true;
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
            if ($propertyName == 'iniciales') { $propertyValue = strtoupper($propertyValue); $this->_estado = true;}        
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
    * Estilos opcionales para la sección pasados como array asociativo, ej.:
    * {'display' => 'none', 'width' => '80%',...} habitualmente usaremos
    * el display que es el más útil... y según el estado...
    */
    function toHTML($style = null) {
        return(parent::toHTML($style));  
    }
    /**
     * Establece el $_estado en función de 
     * si la clase tiene datos o no.
     * Devuelve el resultado como:
     * - true ... tiene datos ('llena')
     * - false .. no tiene datos ('vacía')
     */
    function estado() {
        return(parent::estado());     
    }    
}

/*
PARA TEST USAR
<?php

use nuimsa\clases\Demograficos;
use nuimsa\clases\Solicitud;
use nuimsa\clases\Acude_por;
use nuimsa\clases\Localiza;
use nuimsa\clases\Evolucion;
use nuimsa\clases\Califica;
use nuimsa\clases\Motivo;
use nuimsa\clases\Clinica;
use nuimsa\clases\Incidencias;
use nuimsa\clases\Adjunto;
use nuimsa\clases\Localizacion_protesis;
use nuimsa\clases\Evolucion_protesis;
use nuimsa\clases\Lateralidad_protesis;
use nuimsa\clases\SDRC;

require_once 'Base.php';
require_once 'Demograficos.php';
require_once 'Solicitud.php'; 
require_once 'Acude_por.php'; 
require_once 'Localiza.php'; 
require_once 'Evolucion.php'; 
require_once 'Califica.php'; 
require_once 'Motivo.php'; 
require_once 'Clinica.php'; 
require_once 'Incidencias.php'; 
require_once 'Adjunto.php'; 
require_once 'Localizacion_protesis.php'; 
require_once 'Evolucion_protesis.php'; 
require_once 'Lateralidad_protesis.php'; 
require_once 'SDRC.php';

// instancio las clases
$test = new Demograficos();
$sol = new Solicitud();
$acu = new Acude_por();
$loc = new Localiza();
$evo = new Evolucion();
$cal = new Califica();
$mot = new Motivo();
$cli = new Clinica();
$inc = new Incidencias();
$adj = new Adjunto();
$loc_pro = new Localizacion_protesis();
$evo_pro = new Evolucion_protesis();
$lat_pro = new Lateralidad_protesis();
$sdr = new SDRC();

// Pruebas...
echo '<h2>Salida del ESTADO de la clase de acuerdo al flag $_estado: </h2>';
echo $test->estado ? 'Llena' : 'Vacía';

$test->estado = true;
echo '<h2>Salida del ESTADO de la clase de acuerdo al flag $_estado: </h2>';
echo $test->estado ? 'Llena' : 'Vacía';

$test = new Demograficos("UHY","22/06/2016","","GAMMAGRAFIA OSEA","98787");

echo '<h2>Salida de las iniciales UHY : </h2>';
echo $test->iniciales['value'];

$test->iniciales = "WEDSDE";

echo '<h2>Salida de las iniciales WEDSDE : </h2>';
echo $test->iniciales['value'];

echo '<h2>Salida de parte del valor de una propiedad específica: </h2>';
echo ($test->iniciales['type']);

echo '<h2>Salida como XML: </h2>';
print_r($test->toXML());

echo '<h2>Salida como DOMNode: </h2>';
print_r($test->toDOMNodes());

echo '<h2>Salida como array asociativo: </h2>';
print_r($test->toArray());

echo '<h2>Salida como simple array: </h2>';
print_r($test->toSimpleArray());

echo '<h2>Salida como HTML: </h2>';
echo $test->toHTML();

// Prueba del resto de clases 

echo '<h2>Salida del ESTADO de la clase: </h2>';
echo $sol->estado ? 'Llena' : 'Vacía';

echo '<h2>Salida como HTML: </h2>';
print_r($sol->toArray());


echo '<h2>Salida del ESTADO de la clase Acude_por: </h2>';
echo $acu->estado ? 'Llena' : 'Vacía';

echo '<h2>Salida como Array: </h2>';
print_r($acu->toArray());

echo '<h2>Salida del ESTADO de la clase Localizacion: </h2>';
echo $loc->estado ? 'Llena' : 'Vacía';

echo '<h2>Salida del ESTADO de la clase Localizacion_protesis: </h2>';
echo $loc_pro->estado ? 'Llena' : 'Vacía';

echo '<h2>Salida del ESTADO de la clase Evolucion_protesis: </h2>';
echo $evo_pro->estado ? 'Llena' : 'Vacía';

?>
*/