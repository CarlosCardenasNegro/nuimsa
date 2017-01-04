/* 
Created by: Kenrick Beckett
Name: Chat Engine
*/
// variables globales 
var instanse = false;
var state;
var mes;
var file;
var cont;
var chatInitialState;

// definición y funciones...
function Chat () {
    this.getState = getStateOfChat;
    this.update = updateChat;
    this.send = sendChat;
    this.createWindow = createWindowChat;
    this.destroy = destroyChat;
    this.getLastUser = getLastUser;
    this.play = playChat;
}

function getLastUser() {
    // recupera el usuario 
    // leyendo la última línea
    $.post('process.php', {'function': 'getUser'}).done (function(data) {
        return data;
    });
}

//gets the state of the chat
function getStateOfChat(){
	if(!instanse){
		 instanse = true;
		 $.ajax({
			   type: "POST",
			   url: "process.php",
			   data: {  
			   			'function': 'getState',
						'file': file
						},
			   dataType: "json",
			
			   success: function(data){
				   state = data.state;
				   instanse = false;
			   },
			});
	}	 
}

//Updates the chat
function updateChat(){
	 if(!instanse && temporizador){
		 instanse = true;
	     $.ajax({
			   type: "POST",
			   url: "process.php",
			   data: {  
			   			'function': 'update',
						'state': state,
						'file': file
						},
			   dataType: "json",
			   success: function(data){
				   if(data.text){
						for (var i = 0; i < data.text.length; i++) {
                            $('#chat-area').append($("<p style='padding: 8px 0'>"+ data.text[i] +"</p>"));
                        }								  
				   }
				   document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;
				   instanse = false;
				   state = data.state;
			   },
			});
	 }
	 else {
         // actualizo solo si no estoy pausadfo...
         if (temporizador) {
    		 setTimeout(updateChat, 1500);         
         }
	 }
}

//send the message
function sendChat(message, nickname)
{
    // le paso la hoja de estilos para
    // desde php recuperar el color real
    var css = $( 'link:eq(4)' ).attr('href');    
    updateChat();
    
    $.post('process.php',
            {'function':'send','message':message,'nickname':nickname,'file':file,'css':css},
            'json').done( function(data) {
                updateChat();
            });
    
}

// create chat window
function createWindowChat() {
    var dialogo = "";
    var $fun = '$( "#chat001" ).fadeTo("slow", 0).hide(); chat.destroy()';
    var $tipo = "Chat Room";
    dialogo += "<div id='chat-area' class='w3-padding w3-theme-l3'></div>";
    dialogo += "<form id='send-message-area'>";
    dialogo += "<label>Tu mensaje: </label><br/>";
    dialogo += "<textarea class='w3-theme-l1' id='sendie' maxlength = '200'";
    dialogo += " onkeydown='sendieDown(event)' onkeyup='sendieUp(event)'></textarea>";
    dialogo += "<i class='w3-jumbo w3-text-theme fa fa-stop-circle' aria-hidden='true' onclick='playChat()'></i>"
    dialogo += "</form>";
    return dialogo;
}

function playChat() {
    if ( $( '#send-message-area i' ).hasClass('fa fa-stop-circle')) {
        // pauso chattting
        clearInterval(temporizador);
        temporizador = null;
        $( '#send-message-area i' ).removeClass('fa fa-stop-circle');
        $( '#send-message-area i' ).addClass('fa fa-play-circle');
    } else {
        // reanudo chattting
        temporizador = setInterval(chat.update, 1000);   
        $( '#send-message-area i' ).removeClass('fa fa-play-circle');
        $( '#send-message-area i' ).addClass('fa fa-stop-circle');
    }
}

// aclaro temporizador
// Nota: no destruyo la instancia
function destroyChat() {
        //$( '#userbar p' ).html('DestroyChat');
        clearInterval(temporizador);
        chat = null;
        chatActivo = setInterval(chatStatus, 15000);
        // actualizo estado final
        chatInitialState = state;        
}

// page events management
// key events
function sendieDown (event) {
    var key = event.which;
    if (key >= 33) {
        var maxLength = event.target.maxLength;
        var length = event.target.innerHTML.length;
        if (length >= maxLength) {
            event.preventDefault();
        }
    }
}

function sendieUp(e) {
    if (e.keyCode == 13) {
       var text = $( '#sendie' ).val();
       var maxLength = $( '#sendie' ).attr('maxlength');
       var length = text.length;

        if (length <= maxLength + 1) {
            chat.send(text, chatUser);
            $( '#sendie' ).val('');
        } else {
            $( '#sendie' ).val(text.substring(0, maxLength));
        }
    }
}
