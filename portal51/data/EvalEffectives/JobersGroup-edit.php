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
	
	if($adata->id == 0){ // Добавляем
		@$db->query("INSERT INTO `GZC#JobersGroup` SET groupname='{$adata->groupname}', info='{$adata->info}'");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"groupname" => $adata->groupname,
				"info" => $adata->info
			)
		));
		
	} else { // Исправляем
		@$db->query("UPDATE `GZC#JobersGroup` SET groupname='{$adata->groupname}', info='{$adata->info}' WHERE id={$adata->id}");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"groupname" => $adata->groupname,
				"info" => $adata->info
			)
		));
	}
?>