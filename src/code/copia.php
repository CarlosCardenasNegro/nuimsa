<?php
/**
 * NMT: The Nuclear Medicine Toolkit(tm) (http://www.nuimsa.es)
 * Copywright (c) San Miguel Software, Sl. (http://www.sanmiguelsoftware.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.TXT (https://www.tldrlegal.com/l/mit)
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copywright (c) San Miguel Software (http://www.sanmiguelsoftware.com)
 * @author      Carlos Cárdenas Negro 
 * @link        http://www.nuimsa.es
 * @since       0.1.0
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @version     2.0
 */
 
/**
 * Load constants...
 */
if (!isset($_SESSION)) { 
    session_start();
    $CONFIG = $_SESSION['CONFIG'];
    require $CONFIG . 'paths.php';
}

use function nuimsa\tools\testInput;

require_once ROOT . DS . 'tools/tools.php';

$modo = testInput($_POST['value']);

// datos de partida
//$actualDir = getcwd();

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $esperadoDir = XML;
} else {
    $esperadoDir = '/home/u525741712/public_html';
}

$xmlDir = XML;
//$finalDir = $esperadoDir . '/' . $xmlDir . '/';
$archPacientesFileName = 'datos_pacientes.xml';
$zipArchivo = $archPacientesFileName . '.zip';

$archPacientesFileSize = 0;
$archPacientesFileDate = 0;

$pacientesCount = 0;
$pacienteUltimoIniciales = '';
$pacienteUltimoFecha = 0;
$pacienteUltimoExploracion = '';
$data = '';    

// cambiamos a /xml
chdir($xmlDir);
    
$finalDir = getcwd();

if (!file_exists($archPacientesFileName)) {

    // no existe archivo (muy poco probable,...)
    $data = "Error!";
    $data .= "<p class='w3-center'>El archivo de pacientes \"$archPacientesFileName\" no existe en el directorio \"/$xmlDir/\" o está corrupto!.</p>";
    $data .= "<p class='w3-center'>Pongase en contacto con el administrador del sistema.<br/><p>Muchas gracias</p>";
    $data .= "<p class='w3-center'><i>(No podrá seguir trabajando con la aplicación)</i></p>";
    
} else {
    
    // existe el archivo. Recupera información del archivo
    $archPacientesFileSize = number_format(filesize($archPacientesFileName), 2, ',' , '.');
    $archPacientesFileDate = date( 'd/m/Y', filemtime($archPacientesFileName));
    
    // nombre de la futura copia -datos_pacientes.xml.ddmmYYYY-
    $archPacientesCopia = $archPacientesFileName . '.' . date( 'dmY', filemtime($archPacientesFileName));
                
    // recupero información de los datos
    $xmlDoc = new \DOMDocument('1.0', 'utf-8');
    $xmlDoc->load ($archPacientesFileName);
    
    // creo un objeto XPath y recupero número de pacientes
    $xpath = new \DOMXpath($xmlDoc);            
    $ruta = '/pacientes/paciente';
    $pacientesCount = $xpath->query($ruta);
    $pacientesCount = $pacientesCount->length;
    
    if ( $pacientesCount === 0 ) {
        // 0 datos,...
        $data = "Error!";
        $data .= "<p class='w3-center'>El archivo de pacientes \"$archPacientesFileName\" contiene 0 pacientes o no ha podido ser leido.</p>";
        $data .= "<p class='w3-center'>Pongase en contacto con el administrador del sistema.<br/>Muchas gracias</p>";
        $data .= "<p class='w3-center'><i>(No podrá seguir trabajando con la aplicación)</i></p>";
    } else {        
        // hay pacientes todo parece normal
        // recupero más información (iniciales, exploracion y fecha)
        $ruta = '//paciente[last()]/demograficos/fecha/text()';
        $pacienteUltimoFecha = $xpath->query($ruta);
        $pacienteUltimoFecha = $pacienteUltimoFecha->item(0)->nodeValue;
        
        $ruta = '//paciente[last()]/demograficos/iniciales/text()';
        $pacienteUltimoIniciales = $xpath->query($ruta);
        $pacienteUltimoIniciales = $pacienteUltimoIniciales->item(0)->nodeValue;

        $ruta = '//paciente[last()]/demograficos/exploracion/text()';
        $pacienteUltimoExploracion = $xpath->query($ruta);
        $pacienteUltimoExploracion = $pacienteUltimoExploracion->item(0)->nodeValue;
                       
        switch ($modo) {
            case 'info':
            
                // solo quiero la info
                $data .= "<p class='w3-large'>El archivo de pacientes ";
                $data .= "\"$archPacientesFileName\" original ";
                $data .= "existe en el directorio /$xmlDir/ y contiene ";
                $data .= "<strong>$pacientesCount pacientes</strong>. ";
                $data .= "La información de que disponemos es:</p>";
                $data .= "<table class='w3-table-all w3-hoverable' style='width: 90%; margin: auto'>";
                $data .= "<tr class='w3-theme-d4'><th class='w3-center' colspan='2'>Dato</th><th class='w3-theme-d2 w3-center'>Valor</th></tr>";
                $data .= "<tr><td>Tamaño actual</td><td/><td>$archPacientesFileSize bytes</td></tr>";
                $data .= "<tr><td>Modificado por última vez</td><td/><td>$archPacientesFileDate</td></tr>";
                $data .= "<tr><td>Iniciales del último paciente</td><td/><td>$pacienteUltimoIniciales</td></tr>";
                $data .= "<tr><td>Fecha de exploración del paciente</td><td/><td>$pacienteUltimoFecha</td></tr>";
                $data .= "<tr><td>Exploración realizada</td><td/><td>$pacienteUltimoExploracion</td></tr>";
                $data .= "</table>";                    
                break;
                
            case 'salva':
                // guardo el archivo actual comprimido en zip
                $zip = new ZipArchive();
                        
                if ($zip->open($zipArchivo, ZipArchive::CREATE) !== TRUE)
                {
                    $data = "Error!";
                    $data .= "<p class='w3-large w3-red'>El archivo comprimido \"$zipArchivo\" no ha podido crearse!.</p>";
                    $data .= "<p class='w3-center'>Pongase en contacto con el administrador del sistema.<br/>Muchas gracias</p>";
                    $data .= "<p class='w3-center'><i>(No podrá seguir trabajando con la aplicación)</i></p>";        
                } else {
                    // añado el archivo de pacientes
                    $zip->addFile($archPacientesFileName);
                    $zip->close();
                    $data .= "<p class='w3-center w3-large'>Se ha creado el archivo comprimido \"$zipArchivo\" !.<br/>Muchas gracias</p>";
                }
                break;

            case 'restaura':
                // restauro el último zip que haya
                // confirmo que existe un zip...
                if (!file_exists($zipArchivo)) {
                    // no existe, será la primera vez, o sea, que lo creo
                    $zip = new ZipArchive();
                            
                    if ($zip->open($zipArchivo, ZipArchive::CREATE) !== TRUE)
                    {
                        $data = "Error!";
                        $data .= "<p class='w3-large w3-red'>El archivo comprimido \"$zipArchivo\" no ha podido crearse!.</p>";
                        $data .= "<p class='w3-center'>Pongase en contacto con el administrador del sistema.<br/>Muchas gracias</p>";
                        $data .= "<p class='w3-center'><i>(No podrá seguir trabajando con la aplicación)</i></p>";                    
                    } else {
                        // añado el archivo de pacientes
                        $zip->addFile($archPacientesFileName);
                        $zip->close();    
                        $data .= "<p class='w3-large w3-red'>Se ha creado el archivo comprimido \"$zipArchivo\" por primera vez!.<br/>Muchas gracias</p>";
                    }
                } else {
                    // existe Zip renombro el archivo de pacientes actual (aunque este corrupto)                    
                    rename($archPacientesFileName, $archPacientesCopia);                    

                    // descomprimo el archivo ZIP
                    $zip = new ZipArchive();
                    $zip->open($zipArchivo);
                    
                    if (!$zip->extractTo('./', $archPacientesFileName)) {
                        $data = "Error!";
                        $data .= "<p class='w3-large w3-red'>El archivo comprimido \"$zipArchivo\" no ha podido recuperarse!.</p>";
                        $data .= "<p class='w3-center'>Pongase en contacto con el administrador del sistema.<br/>Muchas gracias</p>";
                        $data .= "<p class='w3-center'><i>(No podrá seguir trabajando con la aplicación)</i></p>";
                    } else {
                        $zip->close();
                        
                        // recupero la información del nuevo archivo de pacientes recien extraido,...
                        $archPacientesFileSize = filesize($archPacientesFileName);
                        $archPacientesFileDate = date( 'd/m/Y', filemtime($archPacientesFileName));
                        
                        // recupero información de los datos
                        $xmlDoc = new DOMDocument('1.0', 'utf-8');
                        $xmlDoc->load ($archPacientesFileName);
                        
                        // creo un objeto XPath y recupero número de pacientes
                        $xpath = new DOMXpath($xmlDoc);            
                        $ruta = '/pacientes/paciente';
                        $archPacientesCount = $xpath->query($ruta);
                        $archPacientesCount = $archPacientesCount->length;
                        
                        // recupero más información (iniciales y fecha)
                        $ruta = '//paciente[last()]/demograficos/fecha/text()';
                        $pacienteUltimoFecha = $xpath->query($ruta);
                        $pacienteUltimoFecha = $pacienteUltimoFecha->item(0)->nodeValue;
                        
                        $ruta = '//paciente[last()]/demograficos/iniciales/text()';
                        $pacienteUltimoIniciales = $xpath->query($ruta);
                        $pacienteUltimoIniciales = $pacienteUltimoIniciales->item(0)->nodeValue;
                
                        $ruta = '//paciente[last()]/demograficos/exploracion/text()';
                        $pacienteUltimoExploracion = $xpath->query($ruta);
                        $pacienteUltimoExploracion = $pacienteUltimoExploracion->item(0)->nodeValue;

                        // la segunda tabla de nuevo a pantalla
                        $data .= "<p class='w3-large'>Hemos guardado el archivo original con el siguiente ";
                        $data .= "nombre \"$archPacientesCopia\" y hemos recuperado la última copia comprimida ";
                        $data .= "existente.</p>";                                            
                        $data .= "<p class='w3-large'>El archivo de pacientes \"$archPacientesFileName\" recuperado desde el ZIP contiene <strong>$pacientesCount pacientes</strong>. La información de que disponemos es:</p>";
                        $data .= "<table class='w3-table-all w3-hoverable' style='width: 80%; margin: auto'>";
                        $data .= "<tr class='w3-theme-d4'><th class='w3-center' colspan='2'>Dato</th><th class='w3-theme-d2 w3-center'>Valor</th></tr>";
                        $data .= "<tr><td>Tamaño actual</td><td/><td>$archPacientesFileSize bytes</td></tr>";
                        $data .= "<tr><td>Modificado por última vez</td><td/><td>$archPacientesFileDate</td></tr>";
                        $data .= "<tr><td>Iniciales del último paciente</td><td/><td>$pacienteUltimoIniciales</td></tr>";
                        $data .= "<tr><td>Fecha de exploración del paciente</td><td/><td>$pacienteUltimoFecha</td></tr>";
                        $data .= "<tr><td>Exploración realizada</td><td/><td>$pacienteUltimoExploracion</td></tr>";
                        $data .= "</table>";                            
                    } //endif
                } //endif                   
            break;
        }// end switch
    } //endif
} //endif

echo $data;
?>