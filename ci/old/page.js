/**
 * página inicial
 */
var $sec = 1;

function test() {
    $val = event.srcElement.text;
    $secActual = '#sec' + $sec;
    $secNueva = '#sec' + $val;
    $ ( $secActual ).fadeTo('slow', 0). hide();
    $ ( $secNueva ).fadeTo('slow', 1);
	// actualizo display
	var $panel = document.querySelector('.w3-pagination');
	var $lista = $panel.querySelectorAll('a');
	// desactivo actual
	$lista.item($sec-1).classList.remove('w3-blue', 'w3-hover-white');
	$lista.item($sec-1).classList.add('w3-hover-pale-red');
	//activo nueva
	$lista.item($val-1).classList.remove('w3-hover-pale-red');
	$lista.item($val-1).classList.add('w3-blue', 'w3-hover-white');
	$sec = $val;
	// actualizo la numeración
	$( '#footer' ).html('Pág. ' + $sec);
    
}

