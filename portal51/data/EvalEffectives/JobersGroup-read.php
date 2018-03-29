<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	$data = array();
//	$adata = (object)$_GET; //json_decode($info);

	$Date = trim ((!empty($_GET['db'])) ? $_GET['db'] : date('Y-m-d') ); // 

    $db = ConnectMyDB('EvalEffective');

	$sql = "select * from `GZC#JobersGroup`";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
