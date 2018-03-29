<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
$org_id  = isset( $_SESSION['portal']['domain_id'] ) ? $_SESSION['portal']['domain_id'] : 0;
/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;


	$data = array();
	$sql = "SELECT * FROM `portal`.`prt#orgs` ORDER BY code";
	if ( $result = $db->query( $sql ) ) {

		/*array_push($data, array(
			"id" => "0",
			"name" => "локально",
			"name_full" => "локально"
			));*/
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'org_id'=>$org_id,'data'=>$data));
	}

?>