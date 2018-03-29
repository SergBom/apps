<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('EvalEffective');

/*---------------------------------------------------------------------------*/
    $DB = ( !empty( $_GET['db'] ) ) ? substr($_GET['db'],0,10) : date('Y-m-01'); // 
	$DE = ( !empty( $_GET['de'] ) ) ? substr($_GET['de'],0,10) : date('Y-m-d'); // 



	$data = array();
	
	//@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
	
	//concat(t3.userFm,' ',t3.userIm,' ',t3.userOt) as FIO,
	$sql = "select t1.*, t2.R1, t2.R2, t4.name as otdel
			from `GZC#GZI` t1
			left join `GZC#Record` t2 on t1.R_id=t2.id
			left join `portal`.`prt#otdels` t4 on t1.otdel_id=t4.id
			WHERE dateOtchet >= '$DB' AND dateOtchet <= '$DE'
			";
	if ( $result = $db->query( $sql ) ) {

/*		array_push($data, array(
			"id" => "0",
			"org_id" => 0,
			"name" => "-",
			"name2" => "-",
			"code" => 0
			));
	*/	
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>