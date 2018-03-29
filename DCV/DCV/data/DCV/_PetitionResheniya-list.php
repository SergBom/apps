<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];
	//$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('DisputCadastralValue');

/*---------------------------------------------------------------------------*/


	$sql = "SELECT * FROM `ref_PetitionResheniya`";
	if ( $result = $db->query( $sql ) ) {
	
/*		array_push($data, array(
				"id"=>"0",
				"code"=>"0",
				"name"=>" ",
				"name_full"=>" "
			));
			*/
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>