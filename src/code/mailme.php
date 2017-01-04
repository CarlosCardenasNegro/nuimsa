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
 *
 *
 * @author    Carlos Cárdenas Negro
 * @version   0.1
 * @copyright copyright San Miguel Software, Sl. - 2016 (http://www.sanmiguelsoftware.com)
 */

/**
 *  Me envía un email para avisarme de pacientes urgentes,...
 * 
 * @param string $_POST['subject'] Titulo del mensaje.
 * @param string $_POST['content'] Contenido del cuerpo del mensaje.
 */

if (isset($_POST['content'])) {

    $content = htmlentities($_POST['content']); 
    
    if (isset($_POST['subject'])) {
        $subject = htmlentities($_POST['subject']);
    } else {
        $subject = "El mensaje no tiene título";
    }

    $to = "carloscardenasnegro@gmail.com";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
    $headers .= "From: <carloscardenasnegro@gmail.com>" . "\r\n";
    if (mail($to, $subject, $content, $headers)) {
        echo "Exito!";        
    } else {
        echo "Error!";
    }
}
