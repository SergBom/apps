<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$adata = (object)$_POST;
	

	@$db->query("DELETE FROM `GZC#GroupsJobsCount` where id={$adata->id}");
	
		
	echo json_encode(array('success'=>'true'));

?>