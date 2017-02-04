<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../recursos/css/w3.css">
  <title id="titulo">Datos del archivo de pacientes</title>
  <link rel="stylesheet" type="text/css" href="testXML.css" />
 </head>

 <body class="w3-content" style="max-width: 1024px">
  
<?php
$datos = "";
$xml = "";
$lon = 0;
$cont = 0;
$recambio = "";
$dir_informe = "http://www.nuimsa.es/scan/";
$primero = $ultimo = "";

$xml=simplexml_load_file("http://www.nuimsa.es/xml/historia.xml") or die("Error: Cannot create object");
if ($xml === false) {
    echo "Ha fallado la carga del archivo XML: datos_pacientes-xml... ";
    echo "se muestran los errores habidos...:";
    foreach(libxml_get_errors() as $error) {
        echo "<br>", $error->message;
    }
   } else {
    echo   "<h1>Datos del archivo de Pacientes (" . count($xml->children()) . ")</h1>";    
    echo '<div class="w3-container" style="width: 70%: margin:auto">';
    echo "<table id='pacientes' align='center'>";
    echo "<col width='15%'/>";
    echo "<col width='15%'/>";
    echo "<col width='30%'/>";
    echo "<tr><th colspan='3' style='color: navyblue; font-size: 15pt'>PACIENTES</th></tr>";
    echo "<tr style='font-size: 13pt'><th>Iniciales</th><th>Fecha (hora)</th><th>Exporación</th></tr>";
    foreach($xml->children() as $pac) {
     $cont += 1;
     if ($cont == 1) {
      $primero = $pac->demograficos->iniciales;
     } else {
      $ultimo = $pac->demograficos->iniciales;
     }
     echo "<tr onclick='Row(" . $cont . ")''>";
     echo "<td><a href='#" . $pac->demograficos->iniciales . "'>" . $pac->demograficos->iniciales . "</a> ";
     if($pac->informe->informe == "on") {
      echo "(<a href='" . $dir_informe . $pac->informe->nombre_informe . ".pdf' target='_blank'>Aporta informe</a>)";
     }
     if ($pac->imagen->imagen == "on") {
      echo "(<a href='" . $dir_informe . $pac->informe->nombre_imagen . ".pdf' target='_blank'>Aporta informe</a>)";
     }
     echo "</td>";
     echo "<td>".$pac->demograficos->fecha." (".$pac->demograficos->hora.")</td>";
     echo "<td style='text-transform: uppercase;'>".$pac->demograficos->exploracion."</td>";
     echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    ?>
    <hr>
    <div>
    <a class="w3-xlarge w3-padding-jumbo w3-left" href="#<?php echo $primero; ?>">Ir al inicio</a>
    <a class="w3-xlarge w3-padding-jumbo w3-right" href="#<?php echo $ultimo; ?>">Ir al final</a>    
    </div>
    <hr>
    <?php 
    
    echo '<div class="w3-section" id="div">';

    foreach($xml->children() as $pac) {     
     echo "<p id='" . $pac->demograficos->iniciales . "'>Paciente " . $pac->demograficos->iniciales . "</p>";
     
     if (!empty($pac->solicitud->solicitud)) {
      echo "Información aportada en la solicitud: " . $pac->solicitud->solicitud."<br />";
     }
     
     echo "(Referido por el paciente: acude por ";
     if ($pac->acude_por->poliartralgias == "on") {
      echo "poliartralgias ";
      }
      if ($pac->acude_por->dolor == "on") {
      echo "dolor ";
      }
     if ($pac->acude_por->molestias == "on") {
      echo "molestias ";
      }
     if ($pac->acude_por->dificultad == "on") {
      echo "dificultad para el movimiento ";
      }
     if ($pac->acude_por->sensaciones == "on") {
      echo " dolor y/o cambios distróficos (calor, frio, cambios de color y/o temperatura, caida o aumento del vello) en la extremidad ";
      }
      if (!empty($pac->acude_por->otras)) {
       echo $pac->acude_por->otras;
      }
            
      // caso de las prótesis,...
      // debo esperar mas datos abajo...
      // debo tener en cuenta si es un recambio...
      if ($pac->acude_por->protesis_cadera == "on" || $pac->acude_por->protesis_rodilla == "on") {
       if(!empty($pac->desde_cuando->fecha_recambio)) {
        $recambio = "recambio de ";
        $fechaRecambio = date_create($pac->desde_cuando->fecha_recambio);
        //$fechaRecambio = $pac->desde_cuando->fecha_recambio;
        $fechaRecambio = date_format($fechaRecambio, 'd/m/Y');
       } else {
        $recambio = "";
       }

       if ($pac->acude_por->protesis_cadera == "on") {
         echo "<span class='realce'>" . $recambio . "prótesis de cadera</span>";
       }
       if ($pac->acude_por->protesis_rodilla == "on") {
        echo "<span class='realce'>" . $recambio . "prótesis de rodilla</span>";
       }
       if ($pac->localiza->lat_dch == "on") {
        echo " <span class='realce'>derecha</span> ";
       }
       if ($pac->localiza->lat_izq == "on") {
        echo " <span class='realce'>izquierda</span> ";       
       }
      
       if ($pac->demograficos->exploracion == "LEUCOCITOS MARCADOS PARA INFECCION OSEA") {
        echo "dolorosa y con sospecha de <span class='realce'>infección</span>.";       
       } else {
        echo " dolorosa y con sospecha de <span class='realce'>movilización</span>.";       
       }

       if (!empty($fechaRecambio)) {
          echo "  <span class='realce'>(fecha de recambio de la prótesis el " . $fechaRecambio . "</span>.";  
         }
      }
       
      // caso específico de la rodilla
      if ($pac->acude_por->protesis_rodilla == "on") {
       if($pac->localiza->loc_CF == "on") {
        echo "las molestias se localizan en el componente femoral ";
       }
       if($pac->localiza->loc_CT == "on") {
        echo "las molestias se localizan en el componente tibial ";
       }
       if($pac->localiza->loc_medial == "on") {
        echo " las molestias son de predominio medial ";
       }
       if($pac->localiza->loc_lateral == "on") {
        echo " las molestias son de predominio lateral ";
       }
       if($pac->localiza->loc_rotula == "on") {
        echo " las molestias se localizan en la región de la rótula ";
       }
       if($pac->localiza->loc_peri == "on") {
        echo " las molestias son periprotésicas ";
       } 
       if($pac->localiza->loc_toda == "on") {
        echo " las molestias son generalizadas sin localización precisa ";
       }              
      }
          
      echo ", que califica como de carácter ";
     if ($pac->califica_como->vago == "on") {
      echo "vago, ";
      }
     if ($pac->califica_como->leve == "on") {
      echo "leve, ";
      }
     if ($pac->califica_como->moderado == "on") {
      echo "moderado, ";
      }
     if ($pac->califica_como->severo == "on") {
      echo "severo, ";
      }
     if ($pac->califica_como->invalidante == "on") {
      echo "muy severo o invalidante. ";
      }
      /*echo "<br/>";*/
      if (!empty($pac->localiza->loc_anatomica)) {
       echo " en ";
       echo '<span class="realce">' . $pac->localiza->loc_anatomica . '</span>';
      }
      if (!empty($pac->localiza->loc_region)) {
       echo '<span class="realce"> referidas a la zona ' . $pac->localiza->loc_region . '</span>';
      }
      /*echo "<br/>";*/
      if (!empty($pac->desde_cuando->fecha_precisa)) {
       if ($pac->acude_por->protesis_cadera == "on" || $pac->acude_por->protesis_rodilla == "on") {
        echo "La prótesis fue implantada el ";
       } else {
        echo '. Los síntomas/signos comenzaron el ';
       }       
       echo $pac->desde_cuando->fecha_precisa;
      }
      if ($pac->desde_cuando->aguda == "on") {
       echo '. Los síntomas/signos son de evolución aguda -menos de 1 mes-'; 
      }
      if ($pac->desde_cuando->subaguda == "on") {
       echo ". Los síntomas/signos son de evolución subaguda -de 1 á 3 meses-"; 
      }
      if ($pac->desde_cuando->cronica == "on") {
       echo ". Los síntomas/signos son de evolución crónica -de 3 á 6 meses-"; 
      }
      if ($pac->desde_cuando->larga_evolucion == "on") {
       echo ". Los síntomas/signos son de larga evolución -más de 6 meses-"; 
      }
      if ($pac->desde_cuando->siempre == "on") {
       echo ". No recuerda inicio de los síntomas"; 
      }
      if (!empty($pac->desde_cuando->fecha_otra)) {
       echo ". Los síntomas/signos comenzaron "; 
       echo $pac->desde_cuando->fecha_otra; 
      }
      /*echo '<br />';*/
      
      if (!empty($pac->motivo->motivo_info)) {
       echo ' y lo(s) atribuye a que ';
       echo $pac->motivo->motivo_info;
      }
      /*echo '<br />';*/
      
      if (!empty($pac->clinica->no_antecedentes)) {
       echo '. No antecedentes traumáticos o sobreesfuerzos en relación con estos hallazgos ';
       echo $pac->clinica->clinica_info;
      }
      if (!empty($pac->clinica->clinica_info)) {
       echo '. Otra información clínica de interés es ';
       echo $pac->clinica->clinica_info;
      }      
     }
     echo ')';
     echo '</div>';
     echo '<form method="post" action="historia.php">';
     echo '<input type="submit" name="submit" value="Refresh page" />';
     echo '</form>';
}
?>

 </body>
</html>