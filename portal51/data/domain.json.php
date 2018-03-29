<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyPDO('portal');
/*---------------------------------------------------------------------------*/




	
	$sql = "-- SELECT concat('1:',id) id,name FROM `prt#orgs` union
			SELECT concat('2:',id) id,name FROM `prt#domains` ORDER BY id";
	
	$data = $db->query( $sql )->fetchAll();

	$c = array('success'=>0,'data'=>$data);
	echo json_encode($c);
	

?>