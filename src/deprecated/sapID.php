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
//namespace php\nuimsa\public_html;

//use function php\nuimsa\public_html\testInput;

require_once 'codigo.php';
//require_once 'variables.php';

/**
 * Pequeña función para devolver 
 * el valor del código SAP a partir
 * de la exploración.
 */

if (!isset($_POST['explo'])) {
    return;
} else {
    $buscado = testInput($_POST['explo']);
}

global $explo;

// a lo bruto,...
foreach ($explo as $key => $value) {
    if ($value['desc_larga'] === $buscado) {
        echo $value['SAPid'];
    }
}
    
?>