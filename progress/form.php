<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["userfile"])) {
    // move_uploaded_file()
    print_r($_SESSION);
}
?>
<html>
<head>
<title>File Upload Progress Bar</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="http://www.w3schools.com/lib/w3.css"/>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
    <div id="test" class="w3-card-4 w3-round">
        <p class="w3-container w3-blue w3-padding-large w3-large">UPLOADING FILES</p>
        <div class="w3-container">
            <p class="w3-large">Seleccione uno o más archivos para descargar :</p>                
            <div id="bar_blank">
               <div id="bar_color"></div>
            </div>
            <div id="status"></div>
            <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" id="myForm" enctype="multipart/form-data" target="hidden_iframe">
                    <input type="hidden" value="myForm" name="<?= ini_get("session.upload_progress.name"); ?>">
                    <input type="file" name="userfile"><p/>
                    <input type="submit" value="Start Upload">
                </form>
            </div>
        </div>
    </div>
    <iframe id="hidden_iframe" name="hidden_iframe" src="about:blank"></iframe>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>