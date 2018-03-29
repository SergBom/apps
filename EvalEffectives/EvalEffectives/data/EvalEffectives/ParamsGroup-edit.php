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
	
//	$adata->rec_id	-> rec_id - id Параметра в таблице Record
//	$adata->id		->
//	$adata->g_id	-> id Группы доступа
	
	if($adata->id == 0){ // Добавляем
		$db->query("INSERT INTO `GZC#ParamsGroup` SET g_id={$adata->g_id}, rec_id={$adata->rec_id}");

		$ID= $db->insertId();
	
	} else { // Исправляем
		$db->query("UPDATE  `GZC#ParamsGroup` SET g_id={$adata->g_id}, rec_id={$adata->rec_id} WHERE id={$adata->id}");

		$ID= $adata->id;

	}
	
//	$sql = "select * from `GZC#v#Jobers` where id=$ID";
//	if ( $result = $db->query( $sql ) ) {
	
//		while ($row = $result->fetch_assoc()) {
//			array_push($data, $row);
//		}
		
		echo json_encode(array('success'=>'true')); //,'data'=>$data));
//	}
	
	
	
?>