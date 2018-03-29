<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

var_dump( $_POST);

$j = json_decode($_POST['data']);

var_dump($j);

echo json_encode(array('success'=>'true',
'data'=>$j
));
?>
