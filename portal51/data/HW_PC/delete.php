<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('HW_PC');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_REQUEST;
	

	$db->query("DELETE FROM `DiskDrive` where main_id={$adata->id}");
	$db->query("DELETE FROM `PhysicalMemory` where main_id={$adata->id}");
	$db->query("DELETE FROM `Processor` where main_id={$adata->id}");
	$db->query("DELETE FROM `main` where id={$adata->id}");
	
	
		
	echo json_encode(array('success'=>'true'));

?>