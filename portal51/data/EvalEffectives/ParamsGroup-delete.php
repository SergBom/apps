<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;

	//print_r($adata);
	
	$db->query("DELETE FROM `GZC#ParamsGroup` where id={$adata->id}");
	
	echo json_encode(array('success'=>'true'));
		
?>