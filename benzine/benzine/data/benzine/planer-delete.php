<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$id = $_POST['id'];


	$db->query( "DELETE FROM tour_ends WHERE tour_id=$id" );
	$db->query( "DELETE FROM tours WHERE tour_id=$id" );
	$db->query( "DELETE FROM planer WHERE id=$id" );
	echo json_encode(array(
		"success" => mysql_errno() == 0
	));
?>