<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$a = (object)$_REQUEST;
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

	
	$sql = "select * from `docs_l2` where id_l1={$a->id}";
	
	$data = $db->query(	$sql )->fetchAll();

	echo json_encode(array('success'=>'true','data'=>$data));	

?>