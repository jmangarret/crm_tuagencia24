<img class="alignMiddle" src="themes/images/icono_user.png" title="Adjuntar Pasaporte">
<link href="https://rawgithub.com/hayageek/jquery-upload-file/master/css/uploadfile.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://rawgithub.com/hayageek/jquery-upload-file/master/js/jquery.uploadfile.min.js"></script>
<div id="fileuploader"></div>
<script>
$(document).ready(function() {
    $("#fileuploader").uploadFile({
        url:"upload.php",
        fileName:"myfile"
    });
});
</script>
<?php
echo "ID: ".$_REQUEST["id"];
?>