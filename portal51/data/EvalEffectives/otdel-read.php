<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/

	$data = array();
	
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
	
	
	$sql = "SELECT *, concat('{',cn,'} - ',name) as name2  FROM `portal`.`prt#otdels` WHERE org_id=$org_id  ORDER BY code";
	if ( $result = $db->query( $sql ) ) {

/*		array_push($data, array(
			"id" => "0",
			"org_id" => 0,
			"name" => "-",
			"name2" => "-",
			"code" => 0
			));
	*/	
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>