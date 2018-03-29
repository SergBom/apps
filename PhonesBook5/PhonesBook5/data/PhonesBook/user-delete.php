<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	$data = array();
	$a = (object)$_POST;
	

	$result = $db->query("DELETE FROM `prt#pbook` where id={$a->id}");
	if($result){
		echo json_encode(array('success'=>'true'));
	}else{
		echo json_encode(array('success'=>'false'));
	}
		

?>