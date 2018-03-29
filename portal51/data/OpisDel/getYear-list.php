<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	//$_SESSION['portal']['user_id']
	//$_SESSION['portal']['username']
	//$_SESSION['portal']['FIO']
	//$_SESSION['portal']['otdel_id']

	$data = array();
	
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('OpisDel');
/*---------------------------------------------------------------------------*/

	$sql = "select DISTINCT Year from `Opis` ORDER BY Year";
	
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
	}

?>