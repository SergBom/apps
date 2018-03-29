<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id   = isset( $_SESSION['portal']['domain_id'] ) ? $_SESSION['portal']['domain_id'] : 0;
//	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
/*---------------------------------------------------------------------------*/
//print_r($_SESSION);
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_GET;

	$g_id = 2;
	//$user_id = 85;

	$dateOtchet = substr($adata->dateOtchet,0,10);
	
    $db = ConnectMyDB('EvalEffective');
	$sql = "CALL `GZC#p#RecGroupDate`('$user_id', '$dateOtchet')";
	//echo $sql;
	//select * from `GZC#v#ParamsGroup` where g_id={$g_id}";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}





		
?>