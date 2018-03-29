<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

$org_id = isset( $portal_auth['domain'] ) ?   ($portal_auth['domain'])? $portal_auth['domain']:1   : 1;

	$data = array();

	$sql = "SELECT * FROM `portal`.`prt#otdels` WHERE org_id=$org_id  ORDER BY name";
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>