<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	//$_SESSION['portal']['user_id']
	//$_SESSION['portal']['username']
	//$_SESSION['portal']['FIO']
	//$_SESSION['portal']['otdel_id']

	$data = array();
//	$info = $_GET['data'];
//	$adata = json_decode($info);
	
	
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('OpisDel');
/*---------------------------------------------------------------------------*/

	$sql = "select * from `Npp` WHERE Year='{$_POST['Year']}'";
	
	if ( $result = $db->query( $sql ) ) {

	
		$row = $result->fetch_assoc();
		array_push($data, $row);
	
		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"Npp" => $row['Npp']
			)
		));	
	}
?>