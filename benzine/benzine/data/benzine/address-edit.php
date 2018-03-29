<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	$info = $_POST['data'];
	$adata = json_decode($info);
//	print_r($adata);
	
	if($adata->new){ // Добавляем
		@$db->query("INSERT INTO address SET city_id={$adata->city_id}, address='{$adata->address}'");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"city_id" => $adata->city_id,
				"address" => $adata->address
			)
		));
		
	} else { // Исправляем
		$db->query("UPDATE address SET city_id={$adata->city_id}, address='{$adata->address}' WHERE id={$adata->id}");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"city_id" => $adata->city_id,
				"address" => $adata->address
			)
		));
	}
	
?>