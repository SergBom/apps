<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

	//array_push($data, array("cyear"=>"*"));

	$data = $db->query("SELECT DISTINCT cyear FROM docs_l1 ORDER BY cyear")->fetchAll();
	echo json_encode(array('success'=>'true','data'=>$data));	
?>