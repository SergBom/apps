<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	$data = array();
	$adata = (object)$_GET; //json_decode($info);

	
	//$date = trim($adata->db)
	
	$Date  = trim ((!empty($_GET['db'])) ? $_GET['db'] : date('Y-m-d') ); // 
    $Date0 = trim ((!empty($_GET['d0'])) ? $_GET['d0'] : date('Y-m-01') ); // 
//$Otdel = trim ((!empty($_GET['otd'])) ? $_GET['otd'] : 0 ); // 
/*---------------------------------------------------------------------------*/
//$DateB = "09.01.".date('Y');
//$r = array(); // Массив со статистикой

    $db = ConnectMyDB('EvalEffective');

	//////////////GZC#p#RecordData(dadaBegin,dataEnd,UserId,OtdelId)
	$sql = "CALL `GZC#p#RecordData`('$Date0','$Date','0','0')";
	if ( $result = $db->query( $sql ) ) {
	
//		if ($params["all"]==1){	array_push($data, ['id'=>"0",'name'=>"=== По всем отделам ==="] );}
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
