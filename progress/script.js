function toggleBarVisibility() {
    var e = document.getElementById("bar_blank");
    e.style.display = (e.style.display == "block") ? "none" : "block";
}

function createRequestObject() {
    var http;
    if (navigator.appName == "Microsoft Internet Explorer") {
        http = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else {
        http = new XMLHttpRequest();
    }
    return http;
}

function sendRequest() {

/*
    var http = createRequestObject();
    http.open("POST", "progress.php");
    http.onreadystatechange = function () { handleResponse(http); };
    http.send(null);
*/    

// in jquery
    $.get('progress.php').done ( function(data) {
        // update bar
        $( '#bar_color' ).css('width', data + '%');

        if (data < 100) {
            setTimeout("sendRequest()", 1000);            
        } else {
            $( '#bar_blank' ).toggle();
        } 
    });
    
}

function handleResponse(http) {
    var response;
    if (http.readyState == 4) {
        response = http.responseText;
        document.getElementById("bar_color").style.width = response + "%";
        document.getElementById("status").innerHTML = response + "%";

        if (response < 100) {
            setTimeout("sendRequest()", 1000);
        }
        else {
            toggleBarVisibility();
            document.getElementById("status").innerHTML = "Done.";
        }
    }
}

function startUpload() {
    $( '#bar_blank' ).toggle();
    setTimeout("sendRequest()", 1000);
}

(function () {
    //document.getElementById("myForm").onsubmit = startUpload;
})();