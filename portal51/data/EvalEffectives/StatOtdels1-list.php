<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

/*	$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
*/
	$data = array();
	$adata = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('EvalEffective');

/*---------------------------------------------------------------------------*/

	// Возвращаем список основных отделов
	//$sql = "SELECT id,name,sname,cn  FROM `prt#otdels` WHERE par_id=0";
	
	$sql = "CALL `GZC#p#Otdels`()";
	
	if ( $result = $db->query( $sql ) ) {

		array_push($data, array(
			'id'=>'10000',
			'name'=>'SUMM',
			'sname'=>'SUMM',
			'cn'=>'summ'
		));

	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>