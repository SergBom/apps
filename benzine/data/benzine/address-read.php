<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/


	$data = array();
	//$sql = "SELECT * FROM address ORDER BY name";
	
	$sql = "select a.id id, a.city_id city_id, a.address address, b.name city_name, concat(b.name,', ',address) address_full
			from `address` a left join city b on a.city_id=b.id
			order by address_full";
	
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>