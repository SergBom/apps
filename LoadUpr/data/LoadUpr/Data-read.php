<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
//	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
//	$org_id   = isset( $_SESSION['portal']['domain_id'] ) ? $_SESSION['portal']['domain_id'] : 0;
//	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
/*---------------------------------------------------------------------------*/
//print_r($_SESSION);
/*---------------------------------------------------------------------------*/

	$data = array();
	$a = (object)$_REQUEST;

	$db = ConnectPDO('LoadUpr');
	
	$sql = "SELECT id, name_full, var_data FROM `vDatas` WHERE otdel_id='{$a->otdel}' AND years='{$a->years}' and months='{$a->months}'";
	//echo $sql;
	
	$data = $db->query( $sql )->fetchAll();
	
	
	echo json_encode(array('success'=>'true','data'=>$data));
		
?>