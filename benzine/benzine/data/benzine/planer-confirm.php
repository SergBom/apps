<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$user_id = isset( $portal_auth['user_id'] ) ?   ($portal_auth['user_id'])? $portal_auth['user_id']:''   : '';
	
	$sql = "UPDATE planer SET
			tour_success={$_POST['confirm']},
			tour_success_user_id='{$user_id}'
			WHERE id={$_POST['id']}";
	$db->query($sql);
	
	echo json_encode(array(
		"success" => mysql_errno() == 0
	));
?>