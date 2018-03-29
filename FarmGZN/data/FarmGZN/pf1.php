<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/

	$data = array();
	$a = (object)$_REQUEST;

	$db = ConnectPDO('FarmGZN');
	
	
	$sql = "SELECT * FROM `refFormaProv1`";
	
	$data = $db->query( $sql )->fetchAll();
	
	
	echo json_encode(array('success'=>'true','data'=>$data));
		
?>