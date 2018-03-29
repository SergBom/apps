<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
$data = array();

for( $i=1998; $i<2026; $i++ ){
	array_push( $data, array('id'=>"$i",'name'=>"$i") );
}

	echo json_encode(array('success'=>'true','data'=>$data));	
?>