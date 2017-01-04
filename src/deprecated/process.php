<?php
/**
 * Utilidad de chat
 */    

$function = $_POST['function'];

$log = array();

switch ($function) {
    case 'getInitialState':
        // al inicio recupero las lineas de 'hoy'

        if (file_exists('chat.txt')) {
            $lines = file('chat.txt');
        }
        
        //$log['state'] = count($lines);
        
        // recupero el número de líneas desde
        // el comienzo del día...
        $log['state'] = getFirstDate($lines);
        
        break;
    case ('getState'):
        // recupero el número de líneas
        
        if (file_exists('chat.txt')) {
            $lines = file('chat.txt');
        }
        
        $log['state'] = count($lines);
        
        break;	

    case ('update'):
        // actualiza el estado 
        // si ha aumentado el número
        // de líneas...
        $state = $_POST['state'];

        if (file_exists('chat.txt')) {
            $lines = file('chat.txt');
        }

        $count = count($lines);
        if ($state == $count) {
            $log['state'] = $state;
            $log['text'] = false;        		 
        } else {
            $text= array();
            $log['state'] = $state + count($lines) - $state;
            foreach ($lines as $line_num => $line) {
                if ($line_num >= $state) {
                    $text[] =  $line = str_replace("\n", "", $line);                           
                }
            }
            $log['text'] = $text; 
        }  
        break;

    case ('send'):
        // añado la línea al archivo
        // etiquetada con usuario fecha y hora.
        $nickname = htmlentities(strip_tags($_POST['nickname']));
        $temp = explode(' ', $nickname);
        $nickname = $temp[0] . ' (<span class="w3-tiny">' . date('d/m/Y H:i') . '</span>)';
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $message = htmlentities(strip_tags($_POST['message']));
        $color = getColor( $_POST['css']);

        if (($message) != "\n") {
            if(preg_match($reg_exUrl, $message, $url)) {
                $message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
            } 
        
            $spam = "<span class='w3-card-2 w3-padding w3-round w3-medium'" .
            " style='margin: 0 15px 0 0; color: white; background:" .
            $color . "'>" .
            $nickname . "</span>";
            fwrite(fopen('chat.txt', 'a'), $spam  . $message = str_replace("\n", " ", $message) . "\n"); 
        }
        break;
        
    case 'getUser':

        if (file_exists('chat.txt')) {
            $lines = file('chat.txt');
        }
        $linea = $lines[count($lines) - 1];

        $arrLinea = explode('>', $linea );
    
        // el nombre esta contenida en el segundo elemento (1)
        $userName = trim(substr($arrLinea[1], 0, 8));
        echo $userName;
        
        break;
}

echo json_encode($log);

exit;

/**
 * Recupera el valor hex del
 * color actual en uso desde
 * el archivo css
 *
 * @param string $css Nombre del archivo css conteniendo el tema de los colores actuales
 * @return string $color El color en hexadecimal como string #FFFFFF.
 */
function getColor ( $css ) {
    // pasada la hoja de estilo 
    // debería recuperar el color real
    $cssFile = '../' . $css;
    $lines = file($cssFile);

    for ($i = 0; $i < count($lines); $i++) {
        if(substr($lines[$i], 0, 12) === '.w3-theme-d3') {
            // recupero el color
            $color = explode('#', $lines[$i]);
            $color = '#' . substr($color[2], 0, 6);
        }
    }    
    return $color;
}

/**
 * Recupera la linea del primer mensaje
 * escrito 'hoy'.
 *
 * @param array $lines Array conteniendo todas las líneas de texto del chat
 * @return integer $i Número de la primera línea con fecha de hoy
 */
function getFirstDate ($lines) {

    $numLines = count($lines);
    $linea = $lines[$numLines - 1];
    $arrLinea = explode('>', $linea );

    // la fecha esta contenida en el último elemento (2)
    $fechaActual = substr($arrLinea[2], 0, 10);
    
    // recorro el array a la inversa
    $i = $numLines - 1;
    while (strpos($lines[$i], $fechaActual) !== false) {
        $i --;        
    }
    
    // devuelvo el número de líneas desde el comienzo del dia
    return $i + 1;
    
}
?>