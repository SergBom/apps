<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();
/*****************************************************************************
	Создаем временную запись в планировщике.
*/
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST; //json_decode($info);
	
	$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];

	//print_r($adata);
	
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
	
	
	
	if($adata->new){ // Добавляем

		$db->query("INSERT INTO planer SET begin_date=NOW()");
		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId()
			)
		));
		
	} else { // Исправляем
	
		$sql = "SELECT IFNULL(id,0) id FROM type_tours WHERE name='".trim($adata->type_tour)."'";
		$tour_id = $db->getOne( $sql );
		$sql = "SELECT IFNULL(id,0) id FROM `portal`.`prt#otdels` WHERE org_id='$org_id' AND name='".trim($adata->otdel)."'";
		$otdel_id = $db->getOne( $sql );
	//	echo "'$tour_id'";
		if ( $tour_id == '' ){ // Нет записей - Добавляем новый тип поездки
			@$db->query("INSERT INTO type_tours SET name='".trim($adata->type_tour)."'");
			$tour_id = $db->insertId();
		}
	
		$sql = "UPDATE planer SET
			begin_date='{$adata->begin_date}',
			begin_time='{$adata->begin_time}',
			duration='{$adata->duration}',
			reference='{$adata->reference}',
			otdel='$otdel_id',
			type_tour='$tour_id',
			user_id='{$user_id}'
			WHERE id={$adata->id}";
		@$db->query($sql);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"begin_date"=>$adata->begin_date,
				"begin_time"=>$adata->begin_time,
				"duration"=>$adata->duration,
				"reference"=>$adata->reference,
				"type_tour"=>$adata->type_tour,
				"tour_success"=>$adata->tour_success
			)
		));
	
	}


?>