<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_GET;

	$org_id = @$adata->org_id;	

	$sql = "SELECT * FROM `portal`.`prt#dolzhnost` WHERE org_id='$org_id' ORDER BY code";
	if ( $result = $db->query( $sql ) ) {
		
		array_push($data, array(
			"id" => "0",
			"org_id" => "0",
			"name" => "-",
			"code" => "0"
			));
		
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>