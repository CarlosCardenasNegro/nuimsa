<?php
/*

 Nuclear Medicine Toolkit
 (c) Carlos Cárdenas Negro
 1 de Marzo de 2016
 Versión 1.0

 Portal WEB para la gestión
 básica de la actividad del 
 servicio de Medicina Nuclear
 
 nombre: footer.php

*/

/* Establezco estado LOCAL para la fecha y hora */
date_default_timezone_set('Europe/London');
$fecha = '    ' . date('d/m/Y') . '    ';
$hora  = '    ' . date('H:i:s') . '    ';
?>

<footer id="footer" class="w3-container w3-black w3-padding-medium">
 <div class="w3-row">
  <div class="w3-third w3-center">
   <p class="w3-small"><i class="fa fa-copyright"></i> <a href="mailto:carloscardenasnegro@gmail.com">Carlos Cárdenas Negro</a> - San Miguel Software (<i class="fa fa-trademark"></i>).</p>
  </div>
  <div class="w3-third w3-center">
   <p class="w3-small">Powered by <a href="http://www.w3schools.com/w3css/default.asp">c3.css</a> (<i class="fa fa-css3"></i>)<p/>
  </div>
  <div class="w3-third w3-center">
   <p class="w3-small"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $fecha; ?><i class="fa fa-clock-o" aria-hidden="true"></i>  <?php echo $hora; ?><p/>
  </div>
 </div>
</footer>
