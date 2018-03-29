<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
//	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id']  : '';

	
	if( isset($_POST['data']) ){
		$adata = json_decode($_POST['data'],true); 
	} else {
		$adata = $_POST;
	}
		
	$data = array();

	//print_r($adata);

    $db = ConnectMyDB('EvalEffective');

	//$on = ($adata['on']==1) ? $adata['on'] : 0;
	//$on = 1;
	
	
	if($adata['id']==0){  // Добавляем
		
		$sql = "INSERT INTO `GZC#RecordCalc` SET
				Punkt='{$adata['Punkt']}',
				C1='{$adata['C1']}',
				C2='{$adata['C2']}',
				formula='{$adata['formula']}'";
		$ID = $db->insertId();
		
	} else {  // Обновляем
		
		$sql = "UPDATE `GZC#RecordCalc` SET
				Punkt='{$adata['Punkt']}',
				C1='{$adata['C1']}',
				C2='{$adata['C2']}',
				formula='{$adata['formula']}'
			WHERE id={$adata['id']}";
		$ID = $adata['id'];			

	}
	//echo $sql;
		$db->query( $sql );
	
	$sql = "SELECT * FROM `GZC#RecordCalc` WHERE id=$ID";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
