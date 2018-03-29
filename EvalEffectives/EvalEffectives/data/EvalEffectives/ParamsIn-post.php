<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	
	if( isset($_POST['data']) ){
		$adata = json_decode($_POST['data'],true); 
	} else {
		$adata = $_POST;
	}
		
	$data = array();

	//print_r($adata);

    $db = ConnectMyDB('EvalEffective');

	$on = ($adata['on']==1) ? $adata['on'] : 0;
	//$on = 1;
	
	
	if($adata['id']==0){  // Добавляем
		
		$sql = "INSERT INTO `GZC#Record` SET
				R1='{$adata['R1']}',
				R2='{$adata['R2']}',
				`on`='". $on ."'";
		$ID = $db->insertId();
		
	} else {  // Обновляем
		
		$sql = "UPDATE `GZC#Record` SET
				R1='{$adata['R1']}',
				R2='{$adata['R2']}',
				`on`='". $on ."'
			WHERE id={$adata['id']}";
		$ID = $adata['id'];			

	}
//	echo $sql;
		$db->query( $sql );
	
	$sql = "SELECT * FROM `GZC#Record` WHERE id=$ID";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
