<?php
$uploaddir = _SERVER["DOCUMENT_ROOT"].'data/sys/media/faq/';
$uploadfile = $uploaddir . basename($_FILES['inputfile']['name']);
 
 
//echo '{"success": true, "msg": "'. htmlspecialchars($_FILES['inputfile']['tmp_name']) .'" }';
//move_uploaded_file($_FILES['inputfile']['tmp_name'], $uploadfile);
 
// $a = print_r( $_FILES );
 
if (move_uploaded_file($_FILES['inputfile']['tmp_name'], $uploadfile)) {

    echo '{"success": true, "file": "'. $uploadfile .'" }';

} else {
    echo '{"Failure": true, "file": "777"}';
}

?>