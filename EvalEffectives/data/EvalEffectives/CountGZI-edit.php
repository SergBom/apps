<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
	print_r($adata);
	
	if($adata->id == 0){ // Добавляем
		$db->query("INSERT INTO `GZC#GroupsJobsCount` SET otdel_id='{$adata->otdel_id}', n_gzi='{$adata->n_gzi}', dateCount='{$adata->dateCount}'");

		$ID = $db->insertId();
		
	} else { // Исправляем
		$db->query("UPDATE `GZC#GroupsJobsCount` SET otdel_id='{$adata->otdel_id}', n_gzi='{$adata->n_gzi}', dateCount='{$adata->dateCount}' WHERE id={$adata->id}");

		$ID = $adata->id;
		
	}
	
	$sql = "select * from `GZC#v#GroupsJobsCount` where id=$ID";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		
		echo json_encode(array('success'=>'true','data'=>$data));
	}
	
	
	
?>