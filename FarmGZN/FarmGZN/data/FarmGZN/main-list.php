<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];
	//$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';

	$data = array();
	$a = (object)$_REQUEST; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('FarmGZN');

/*---------------------------------------------------------------------------*/



	$sql = "SELECT * FROM `vErrors`";



	$data = $db->query( $sql )->fetchAll();

/*	if($data['error_type']==0){
		$data['error_type']='Не устранено';
	} else {
		$data['error_type']='Устранено';
	}
	*/
	

	echo json_encode(array('success'=>'true','data'=>$data));



?>