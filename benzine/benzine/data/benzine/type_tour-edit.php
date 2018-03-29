<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;

	if($adata->id == 0){ // Добавляем
		$sql = "INSERT INTO type_tours SET name=?s";
		@$db->query($sql,$adata->name);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"name" => $adata->name
			)
		));
		
	} else { // Исправляем
		$sql = "UPDATE type_tours SET name=?s WHERE id={$adata->id}";
		@$db->query($sql,$adata->name);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"name" => $adata->name
			)
		));
		
	}
	
/*	$info = $_POST['contatos'];

	$data = json_decode(stripslashes($info));

	$name = $data->name;
	$email = $data->email;
	$phone = $data->phone;

	//consulta sql
	$query = sprintf("INSERT INTO contact (name) values ('%s')",
		mysql_real_escape_string($name)
		);
	$sql = "INSERT INTO stats SET pid=?i,dt=CURDATE(),?u ON DUPLICATE KEY UPDATE ?u";
	$db->query($sql,$pid,$data,$data);
	$rs = mysql_query($query);

	echo json_encode(array(
		"success" => mysql_errno() == 0,
		"contatos" => array(
			"id" => mysql_insert_id(),
			"name" => $nome
		)
	));
	
	*/

?>