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

/**
 * Carga de caso Quiz.
 * En el GET se pasa el modo: 
 * (1) nuevo ... para caso nuevo
 * (2) edicion .. para edición de un caso
 * (3) borrado .. para borrado de un caso
 */
use function nuimsa\tools\conviertefecha;
use function nuimsa\tools\testInput;

use nuimsa\clases\Casonuevo;

require_once ROOT . DS . 'src/tools/tools.php';

$cls = "w3-btn w3-wide w3-theme-d1 w3-half w3-w3-hover-theme w3-hover-theme:hover w3-margin-bottom";
$cls1 = "w3-btn w3-wide w3-theme-d4 w3-half w3-w3-hover-theme w3-hover-theme:hover w3-margin-bottom";

// puedo entrar con un POST o con un GET...
// vengo de navbar ... entro con un GET
if (count($_GET) > 0) { $modo = testInput($_GET['modo']); }
// vengo de showCaso ... entro con un POST
if (count($_POST) > 0 ) { 
    $casoID = testInput($_POST['casoID']);
    $modo = testInput($_POST['modo']);    
}

switch ($modo) {
    
    case 'nuevo':
        // inicio el caso
        $casoNuevo = new Casonuevo();
        break;
        
    case 'editar':
    
    case 'borrar':
        // llego aquí desde showCaso()
        // puedo editar o borrar el caso
        
/*        
        $sql = "SELECT `quiz`.*, `categoria`.*, `quiz_images`.*, `quiz_tags`.* FROM `quiz` LEFT JOIN `categoria` ON `categoria`.`id` = `quiz`.`categoria_id` LEFT JOIN `quiz_images` ON `quiz_images`.`quiz_id` = `quiz`.`id` LEFT JOIN `quiz_tags` ON `quiz_tags`.`quiz_id` = `quiz`.`id` WHERE (`quiz`.`id` = $casoID)";
*/
        if ($modo === 'editar') {
            // edición recupero la secuencia... 
            // (1) quiz; (2) quiz_tags; (3) quiz_images
            $sql  = "select * FROM quiz where `id` = $casoID";
            $sql1 = "SELECT `tags`.`id` FROM `quiz_tags` LEFT JOIN `tags` ON `quiz_tags`.`tag_id` = `tags`.`id` WHERE (`quiz_tags`.`quiz_id` = $casoID)";
            $sql2 = "SELECT * FROM `quiz_images` WHERE `quiz_id` = $casoID";
        } else {
            // borrado
            $sql = "DELETE FROM `quiz_tags` WHERE `quiz_id` = $casoID";
            $sql1 = "DELETE FROM `quiz_images` WHERE `quiz_id` = $casoID";
            $sql2 = "DELETE FROM `quiz` WHERE `id` = $casoID";
        }
        
        /**
         * Set parameters according to Host (local o web server)
         */
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            $servername = 'localhost';
        } else {
            $servername = 'mysql.hostinger.es';
        }
        $username = 'u525741712_quiz';
        $password = 'XpUPQEthoAcKK5Y30b';    
        
        try {
            $conn = new \PDO("mysql:host=$servername;dbname=u525741712_quiz", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
            if ($modo === 'editar') {
                // recupero la consulta de Todos los casos
                // (1) quiz
                $stmt = $conn->query($sql);    
                $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
                $quiz = $stmt->fetchAll();

                // (2) quiz_tags
                $stmt = $conn->query($sql1);    
                $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
                $quiz_tags = $stmt->fetchAll();
                
                // (3) quiz_images
                $stmt = $conn->query($sql2);    
                $stmt->setFetchMode(\PDO::FETCH_ASSOC); 
                $quiz_images = $stmt->fetchAll();
                
                $quiz = $quiz[0];
                foreach ($quiz_tags as $key => $value) {
                    $tags[] = $value['id'];                
                }
                

                if ($quiz) {
                    $temp =  explode('/', $quiz['icon']);
                    $casoNuevo = new Casonuevo(
                        $temp[0],
                        $quiz['categoria_id'],
                        $tags,
                        conviertefecha($quiz['dia'], 'local'),
                        $quiz['title'],
                        $quiz['subtitle'],
                        $quiz['contenido']
                    );
                }
            } else {
                // borrado
                $conn->exec($sql);
                $conn->exec($sql1);
                $conn->exec($sql2);
                $conn = null;
                exit;                
            }
            
            $conn = null;        
        }
        catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
        break;
 }

// salida a pantalla
?>

<!-- modal -->
<style>
/* Estilo especial para el campo newTag */
input[name=newTag] { position: absolute; top:5%; left:50%; width: 10%; margin:auto; border: 1px solid lightgrey; margin-bottom:10px }
</style>
<div class="popup">
    <div class="w3-container" style="position:relative; top: 10%; width:70%; margin: auto">
        <!-- cuerpo del formulario -->
        <div id="casonuevo" class="w3-container w3-theme-l3 w3-card-16 w3-round">
            <br/>
            <form id="caso" method="post" enctype="multipart/form-data">
                <!-- salida a pantalla -->
                <?php
                $casoNuevo->toHTML("display: block");
                ?>
                <div id="botonera" class="w3-container w3-padding-xlarge">
                    <button class="<?= $cls ?>" id="submit">Envia formulario</button>
                    <button class="<?= $cls1 ?>" id="cancel" onclick="$( '#dialogo' ).html(''); cargaPag('inicio.php')">Cancelar</button>
                </div>
            </form>
            <!-- Campo especial para los tags nuevos... -->
            <input type="text" name="newTag" value="" /><p/>
        </div>
    </div>
</div>
<br/>
<script>
$( function() {    
    // initial actions
    // posiciona y oculta el edit fields para new tags
    var top = ($( '#tag' ).position().top + $( '#tag' ).height() + 28).toString() + 'px';
    var left = $( '#tag' ).position().left.toString() + 'px';
    var width = $( '#tag' ).width().toString() + 'px';
    $( 'input[name=newTag]' ).hide().css({'top':top, 'left':left, 'width':width});
    
    // añado al select la opción de "Nueva etiqueta"...
    var ultima = $( '#tag option' ).length
    var html = '<option class="w3-text-theme" value="9999">*** Añadir etiqueta nueva ***</option>';
    var option = $( '#tag option' ).eq(ultima - 1);
    $( option ).after(html);
    
    // event managers    
    $( '#tag' ).click( function(event) { 
        if(event.target.innerHTML === "*** Añadir etiqueta nueva ***") {
            $( 'input[name=newTag]' ).fadeTo('slow', 1).focus();    
        };    
    });

    $( 'input[name=newTag]' ).change( function () {
//        if( $( 'input[name=newTag]' ).data("submitted") === true) {
//            event.preventDefault();
//            event.stopPropagation();
//            // lo preparo para una nueva submisión
//            $( 'input[name=newTag]' ).data("submitted") === false;
//        } else {
//            $( 'input[name=newTag]' ).data("submitted", true); 

            var nuevoTagValue = null;
            
            var val = $(this).val();
            var ult = $( '#tag option' ).length;
            var opt = $( '#tag option' ).eq(ult-2);

            /**
             * Los nuevos tag se iran numerando sucesivamente
             * les añado la descripción separada por un guión.
             * Al recuperarlos debo quedarmen solo con el número
             * antes de añadir uno nuevo... me gustaría hacerlo mejor
             * posiblemente añadiendo un campo hidden donde guardaría
             * los nuevos tags,...
             */
            var pos = (opt.val()).indexOf('-'); 
            if ( pos === -1) {
                var nuevoTagValue = (Number(opt.val()) + 1).toString() + '-' + val;            
            } else {
                var valor = (opt.val()).substr(0, pos);
                var nuevoTagValue = (Number(valor) + 1).toString() + '-' + val;                
            }
            var app = "<option value='" + nuevoTagValue + "' selected='selected'>" + val + "</option>";
            // añado option
            $( opt ).after(app);
            
            // deselecciono 'new'
            $( '#tag option' ).eq(ult).prop({'selected':false})
            
            // Podría, pero no voy a modificar el tamaño para que no crezca indefinidamente
            //
            //var size = Number($( 'select' ).attr('size')) + 1;
            //$( 'select' ).attr('size', size);
            //
            // reseteo
            $(this).val('');
            $( 'input[name=newTag]' ).hide();
            
            //event.preventDefault();
            //event.stopPropagation();
//        }
    });
    
    $( 'input[name=newTag]' ).keypress( function (event) {
        // evito Form submision al pulsar Enter...
        if (event.which === 13) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    
    $( 'input[name="imagenes[]"]').on('change', function () {

        var tabla = null;        
        var files = $( this ).prop('files');
        var names = "";
        
        if ( files.length > 0) {
            // guardo los nombres en names[]
            for (var i = 0; i < files.length; i++) {
                names += files[i].name + ';';
            }
            $( '#nam' ).attr('value', names.substr(0, names.length - 1));
            var tabla = creaTabla(files);
            /*
            tabla  = "<div id='img_tabla' class='w3-container w3-small' style='width:40%;margin:auto'>";
            tabla += "<table class='w3-table-all'>";
            tabla += "<tr><th class='w3-center w3-theme-d3'>Imagenes seleccionadas para enviar</th></tr>";
            for (var i = 0; i < files.length; i++) {
                tabla += "<tr><td>" + files[i].name + "</td></tr>";        
            }
            tabla += "</table></div>";
            */
            $( this ).after(tabla);
        } else {
            if (document.getElementById('img_tabla') !== null) {
                $( '#img_tabla' ).remove();
            }
        }
   });

    $( 'form#caso' ).submit (function(event) {        
        var datos = new FormData($(this)[0]);
        datos.append('rutina', 'upload');
        test(datos);
        event.preventDefault();
        event.stopPropagation();
    });
    
    $( '#botonera').on('click', function() {
        var boton = event.target.id;
        if (boton == 'cancel') { return true; }
    });
});
</script>
