<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	//$_SESSION['portal']['user_id']
	//$_SESSION['portal']['username']
	//$_SESSION['portal']['FIO']
	//$_SESSION['portal']['otdel_id']

	$data = array();
	$a = (object)$_GET; //json_decode($info);

	//print_r($a);
	$limit=$a->limit;
	$page =$a->page;
	$start=$a->start;
	
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('HW_PC');
/*---------------------------------------------------------------------------*/

	$sql = "select SQL_CALC_FOUND_ROWS * from `v_main` where 1=1"; // $filter";
	if( isset($a->Year) ) {$sql .= " AND Year={$a->Year}";}
	$sql .= " ORDER BY DNSHostName ";
	//$sql .= " LIMIT $start,$limit";

	
	if ( $result = $db->query( $sql ) ) {
		
		$total_rows=$db->query("select FOUND_ROWS()")->fetchColumn();
		while ($row = $result->fetch()) {
			array_push($data, $row);
		}

		$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
		echo json_encode($c);
	}

?>