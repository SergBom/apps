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
	
	//print_r($adata->org_id);
	
	$org_id  = isset( $adata->org_id ) ? $adata->org_id : 1;
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/

	//$sql = "SELECT id,name,concat('{',cn,'} - ',name) as n FROM `prt#otdels` ORDER BY code, n";
	$sql = "SELECT * FROM `prt#dolzhnost` WHERE org_id='$org_id' ORDER BY code";

	if ( $result = $db->query( $sql ) ) {

		array_push($data, array(
				"id"=>"0",
				"code"=>"0",
				"name"=>" "
			));
	
		while ($row = $result->fetch_assoc()) {
			
			array_push($data,  $row);
			/*array(
				"id"=>$row['id'],
				"code"=>$row['code'],
				"name"=>$row['name'],
				"sname"=>$row['sname']
			));*/
			
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>