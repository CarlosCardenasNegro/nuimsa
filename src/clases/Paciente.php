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
 * Clase Paciente.
 * 
 * Representa a todos los datos de pacientes
 * y todas sus manipulaciones posibles.
 *
 */
class Paciente {
    // properties
    // file on disk
    private $_filePath;
    private $_fileName;
    private $_fullFileName;
    private $_fileSize;
    private $_fileDate;
    
    // file loaded in memory
    private $_xmlDoc;

    // file contains
    private $_numPacientes;
    private $_inicialesUltimoPaciente;
    private $_fechaUltimoPaciente;
    private $_exploracionUltimoPaciente;    
    
    // utils
    private $xpath;
    private $temp;

    // methods
    //construct
    function __construct (...$param) {
        // defaults
        $this->_filePath = 'xml';
        $this->_fileName = 'datos_pacientes.xml';

        // argumentos pasados (solo se pueden pasar el path y el nombre...)
        switch (func_num_args()) {
            // se pasaron valores
            case 1:
                $this->_filePath = $param[0];                
                break;
            case 2:
                $this->_filePath = $param[0];                
                $this->_fileName = $param[1];                
                break;
        }
        
        // cargo el archivo en memoria o lo creo si no existe
        $this->_fullFileName = $this->_filePath . DIRECTORY_SEPARATOR . $this->_fileName;

        if (!file_exists($this->_fullFileName)) {
            // si no existe lo creo vacío
            $this->_xmlDoc = new \DOMDocument('1.0', 'utf-8');
            $this->_xmlDoc->saveXML($this->_fullFileName);
        } else {
            // lo cargo en memoria
            $this->_xmlDoc = new \DOMDocument('1.0', 'utf-8');
            $this->_xmlDoc->load ($this->_fullFileName);
        }

        // get file information from filesystem
        $this->_fileSize = number_format(filesize($this->_fullFileName), 2, ',' , '.');
        $this->_fileDate = date( 'd/m/Y', filemtime($this->_fullFileName));        

        // get xml info
        // get a new xPath instance
        $xpath = new \DOMXpath($this->_xmlDoc);            
        
        // (1) número de pacientes
        $temp = $xpath->query('/pacientes/paciente');
        $this->_numPacientes = $temp->length;

        if ($this->_numPacientes  === 0) {
            // inicio datos a ''
            $this->_fechaUltimoPaciente = '';
            $this->_inicialesUltimoPaciente = '';
            $this->_exploracionUltimoPaciente = '';
        } else {
            // hay pacientes todo parece normal
            // recupero información (iniciales, exploracion y fecha)
            $temp = $xpath->query('//paciente[last()]/demograficos/iniciales/text()');
            $this->_inicialesUltimoPaciente = $temp->item(0)->nodeValue;
            $temp = $xpath->query('//paciente[last()]/demograficos/fecha/text()');
            $this->_fechaUltimoPaciente = $temp->item(0)->nodeValue;
            $temp = $xpath->query('//paciente[last()]/demograficos/exploracion/text()');
            $this->_exploracionUltimoPaciente = $temp->item(0)->nodeValue;
        }        
    }

    /**
     * Recupera la información del archivo de pacientes
     * como un array asociativo 
     */
    function getFileInfo() {
        return array(
            'filesize' => $this->_fileSize,
            'filedate' => $this->_fileDate
        );
    }

    /**
     * Recupera la información de los datos xml contenidos
     */
     function getDataInfo() {
         return array(
            'numPacientes' => $this->_numPacientes,
            'inicialesUltimoPaciente' =>  $this->_inicialesUltimoPaciente,
            'exploracionUltimoPaciente' => $this->_exploracionUltimoPaciente
         );
     }
    
    /**
     * Salva el archivo como ZIP
     * 
     * @param string $filename Nombre del archivo xml
     * @return boolean true o false
     */
    function saveZIP($filename) {
        // guardo el archivo actual comprimido en zip
        $zip = new \ZipArchive();
        $zipArchivo = $filename . '.zip';
        
        if ($zip->open($zipArchivo, \ZipArchive::CREATE) !== TRUE)
        {
            return false;
        } else {
            // añado el archivo de pacientes
            $zip->addFile($filename);
            $zip->close();
            return true;
        }        
    }

    /**
     * Restaura el archivo ZIP
     * 
     * @param type $filename Nombre del archivo zip a restaurar
     * @return boolean true o false
     */
    function retoreZIP($filename) {
        // descomprimo el archivo ZIP
        $zip = new \ZipArchive();
        $zip->open($filename);

        if (!$zip->extractTo('./', $this->_fullFileName)) {
            return false;
        } else {
            $zip->close();
            return true;
        }        
    }
}