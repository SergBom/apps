<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/

	$sql = "SELECT * FROM `vS_Tables` WHERE a_id={$a->a_id} ORDER BY field_order";
	$data = $db->query( $sql )->fetchAll(); 
	
	//var_dump($data);
	
	echo json_encode(array('success'=>'true','data'=>$data));
?>
