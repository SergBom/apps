<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	//$info = $_POST['data'];
	$adata = (object)$_POST; //json_decode($info);
	//print_r($adata);
	
	
	
	$sql = "SELECT IFNULL(id,0) id FROM type_tours WHERE name='".trim($adata->type_tour)."'";
	$tour_id = $db->getOne( $sql );
//	echo "'$tour_id'";
	if ( $tour_id == '' ){ // Нет записей - Добавляем новый тип поездки
		@$db->query("INSERT INTO type_tours SET name='".trim($adata->type_tour)."'");
		$tour_id = $db->insertId();
	}
	
	

	
	
	if($adata->new){ // Добавляем
		$sql = "INSERT INTO planer SET 
			begin_date='{$adata->begin_date}',
			begin_time='{$adata->begin_time}:00',
			duration='{$adata->duration}',
			type_tour='$tour_id',
			reference='".trim($adata->reference)."',
			user_id='{$portal_auth['user_id']}'
		";
		$db->query($sql);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(), //$db->insertId(),
				"begin_date" => $adata->begin_date,
				"begin_time" => $adata->begin_time
				'duration'=>$adata->duration,
				'type_tour'=>$tour_id,
				'reference'=>trim($adata->reference),
				'user_id'=>$portal_auth['user_id']
			)
		));
		
	} else { // Исправляем
//		$sql = "UPDATE type_tours SET name=?s WHERE id={$adata->id}";
//		@$db->query($sql,$adata->name);
/*
		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"name" => $adata->name
			)
		));
	*/	
	}


?>