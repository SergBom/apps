<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	
	
	$org_id  = 1;
	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('portal');

/*---------------------------------------------------------------------------*/

	//$sql = "SELECT id,name,concat('{',cn,'} - ',name) as n FROM `prt#otdels` ORDER BY code, n";
	$sql = "SELECT * FROM `prt#dolzhnost` WHERE org_id='$org_id' ORDER BY code";

	$result = $db->query( $sql );

	array_push($data, array(
			"id"=>"0",
			"code"=>"0",
			"name"=>"---"
		));
	
	while ($row = $result->fetch()) {
			
		array_push($data,  $row);
			
	}

	echo json_encode(array('success'=>'true','data'=>$data));
	

?>