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

    $db = ConnectPDO('LoadUpr');

/*---------------------------------------------------------------------------*/



	if( @$a->query == "0" ){
		$sql = "SELECT DISTINCT years FROM `Settings` WHERE `close`=0 ORDER BY years desc";
	} else if( @$a->query == "1" ){
		$sql = "SELECT DISTINCT years FROM `Settings` WHERE `close`=1 ORDER BY years desc";
	} else {
		$sql = "SELECT DISTINCT years FROM `Settings` ORDER BY years desc";
	}
	
	$data = $db->query( $sql )->fetchAll();

	echo json_encode(array('success'=>'true','data'=>$data));

?>