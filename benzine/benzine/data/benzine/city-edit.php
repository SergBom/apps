<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
	if($adata->id == 0){ // Добавляем
		@$db->query("INSERT INTO city SET name='{$adata->name}'");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"name" => $adata->name
			)
		));
		
	} else { // Исправляем
		@$db->query("UPDATE city SET name='{$adata->name}' WHERE id={$adata->id}");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"name" => $adata->name
			)
		));
	}
?>