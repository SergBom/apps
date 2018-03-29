<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_REQUEST;
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/

	$refer  = ( isset($a->refer) ) ? ( $a->refer=='true' ) ? 1:0 :0;
	


	$sql = "SELECT * FROM `A_Tables` WHERE del=0 AND refer=$refer";
	$data = $db->query( $sql )->fetchAll();
	
	echo json_encode(array('success'=>'true','data'=>$data));

?>