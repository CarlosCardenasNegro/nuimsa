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
use function nuimsa\tools\getUserName;
use function nuimsa\tools\testInput;

require_once ROOT . DS . 'src/tools/tools.php';

/**
 * Index.php - Script principal que lanza el resto de la aplicación.
 * 
 * Forma el esqueleto básico del resto y es donde se 
 * cargan todas las páginas.
 */

/*
$w3css = "http://www.w3schools.com/lib/w3.css";
$awesome = "http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css";
$jquery = "https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js";
$color = "red.css";
$headroom = 'https://npmcdn.com/headroom.js';
*/

// temporalmente lo cargo todo desde local..¡¡¡ por el problema de hospiten
// Nota: lo que va para HTML no puede llevar un path absoluto por eso uso NUIMSA
// el cliente le añade http://localhost que equivaldría a /www/home/...
$w3css      = NUIMSA . "recursos/css/w3.css";
$awesome    = NUIMSA . "recursos/font-awesome/css/font-awesome.min.css";
$jquery     = NUIMSA . "recursos/jquery/jquery-2.2.4.min.js";
$headroom   = NUIMSA . "recursos/headroom/headroom.js";
// excepto, las fuentes de Google que no pueden cargarse desde local,... es lo único...
$googleFonts = 'https://fonts.googleapis.com/css?family=Open+Sans:300|Lato:100';
// codigo propio
if (isset($_COOKIE['colores'])) {
    $color = testInput($_COOKIE['colores']);
} else {
    setcookie('colores', 'red.css', time()+(365*24*60*60));
    $color =  'red.css';
}
//$color      = isset($_COOKIE['colores']) ?  $_COOKIE['colores'] : 'red.css';

$color      = NUIMSA  . 'webroot/css/colores/' . $color; 
$carrousel  = NUIMSA  . 'webroot/css/carrousel.css';
$main_css   = NUIMSA  . 'webroot/css/main.css';
$chat_js    = NUIMSA  . 'webroot/js/chat.js';
$main_js    = NUIMSA  . 'webroot/js/main.js';
$esculapio_ico  = NUIMSA  . 'webroot/img/icons/atomo_esculapio_ico.svg';
$esculapio  = NUIMSA  . 'webroot/img/atomo_esculapio2.svg';
?>
<!doctype html>
<html lang="es-ES">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="Description" content="Utilidad simple para la gestión diaria de la unidad de medicina nuclear">
<meta name="robots" content="noindex,nofollow">
<title>NM Toolkit</title>
<!-- css -->
<link rel="stylesheet" type="text/css" href="<?= $w3css ?>"/>
<link rel="stylesheet" type="text/css" href="<?= $awesome ?>" />
<link rel="stylesheet" type="text/css" href="<?= $googleFonts ?>">
<link rel="stylesheet" type="text/css" href="<?= $carrousel ?>" />
<link id="color" rel="stylesheet" type="text/css" href="<?= $color ?>" />
<link rel="stylesheet" type="text/css" href="<?= $main_css ?>" />
<!-- js -->
<script src="<?= $jquery ?>"></script>
<script src="<?= $chat_js ?>"></script>
<script src="<?= $main_js ?>"></script>
<script src="<?= $headroom ?>"></script>
<!-- favicon -->
<link rel="shortcut icon" href="<?= NUIMSA . 'webroot/favicon.ico' ?>"  />
</head>
<body class="w3-theme">
    <!-- (0) carga la barra de usuario -->
    <?php require_once APP . 'userbar.php' ?>
    <!-- (1) carga el menu:  -->
    <span id="menuSlot">
        <?php require_once APP . 'navbar.php'; ?>
    </span><br/>
    <!-- (2) Contenedor de las páginas -->
    <span id="cuerpo"></span>
    <!-- (3) Contenedor de los diálogos -->
    <span id="dialogo"></span>       
    <!-- (4) Carga el footer -->
    <?php require_once APP . 'footer.php'; ?>
    <!-- (5) Embed el sonido -->
    <audio id="chatSound" src="<?= NUIMSA . 'webroot/sounds/Airplane.mp3' ?>" type="audio/mpeg"></audio>
    <audio id='chatInit'  src="<?= NUIMSA . 'webroot/sounds/Bell.mp3' ?>" type='audio/mpeg'></audio>
    <audio id='nature'    src="<?= NUIMSA . 'webroot/sounds/Nature.ogg' ?>" type='audio/ogg'></audio>
</body>
<script>
$( function() {
    // efectos al cargar la página (slideUp)
    $(window).scroll(function() {
      $(".slideanim").each(function(){
        var pos = $(this).offset().top;
        var winTop = $(window).scrollTop();
        if (pos < winTop + 800) { $(this).addClass("slide"); }
      });
    });
    // desplazamiento suave de las paginas
    $( 'a[href^="#"]' ).on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({ scrollTop: target.offset().top - 74 }, 1000);
        }
    });
    // inicia el headroom component
    var header = document.querySelector("#menubar");
    if(window.location.hash) { header.classList.add("headroom--unpinned"); }    
    var headroom = new Headroom(header, { tolerance: { down : 20, up : 20 }, offset : 60 });
    headroom.init();
});
</script>

</html>