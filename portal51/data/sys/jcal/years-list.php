<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	$data = array();
//	$adata = (object)$_GET; //json_decode($info);

	$year = trim ((!empty($_GET['year'])) ? $_GET['year'] : 2222 ); // 

    $db = ConnectMyDB('portal');

	$sql = "select distinct c_year from `prt#jcal`";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
