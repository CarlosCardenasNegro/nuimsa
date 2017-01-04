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
 
use function nuimsa\tools\autenticar;
use function nuimsa\tools\getUsers;
use function nuimsa\tools\getUserName;
use function nuimsa\tools\testInput;

require_once ROOT . DS . 'tools/tools.php';

/**
 * Autentica.php - Basic Autentificación para acceso a profesionales.
 */ 
$password = getUsers();
$users = array_keys($password);

if(!isset($_SERVER['PHP_AUTH_USER'])) {
    // envío header... al inicio
    header ('WWW-Authenticate: Basic realm="Sistema de autenticación"');
    header ('HTTP/1.0 401 Unauthorized');
    //echo "Introducir un ID y contraseña válidos para acceder a NMToolkit\n";
} else {
    $user = testInput($_SERVER['PHP_AUTH_USER']);
    $pass = testInput($_SERVER['PHP_AUTH_PW']);

    if (in_array($user, $users)) {
        if ($pass === $password[$user]) {
            // Todo correcto hemos entrado
            $userName = getUserName($user);
            echo "ok - $userName";
        } else {
            // envío header... error en password
            header ('WWW-Authenticate: Basic realm="Sistema de autenticación"');
            header ('HTTP/1.0 401 Unauthorized');
            echo "Introducir una <strong>CONTRASEÑA</strong> válida para acceder a NMToolkit\n";
        }
    } else {
        // envío header... error en USUARIO
        header ('WWW-Authenticate: Basic realm="Sistema de autenticación"');
        header ('HTTP/1.0 401 Unauthorized');
        echo "Introducir un <strong>USUARIO</strong> válido para acceder a NMToolkit\n";
    }
}

