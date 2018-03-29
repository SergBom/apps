<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('DCVm');

/*---------------------------------------------------------------------------*/


	$sql = "SELECT * FROM `{$a->table}`";
	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	
	echo json_encode(array('success'=>'true','data'=>$data));

?>