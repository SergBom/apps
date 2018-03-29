<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	$info = $_POST['data'];

	$data = json_decode($info);
	
	$db->query( "DELETE FROM `prt#groups_app` WHERE id={$data->id}" );

	echo json_encode(array(
		"success" => mysql_errno() == 0
	));
		
?>