<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_gzn');
/*---------------------------------------------------------------------------*/

	$user_id = isset( $portal_auth['user_id'] ) ?   ($portal_auth['user_id'])? $portal_auth['user_id']:''   : '';
	
	$sql = "UPDATE `refusals` SET
			itsOk={$_POST['confirm']},
			userUpdate='{$user_id}'
			WHERE id={$_POST['id']}";
	$db->query($sql);
	
	echo json_encode(array(
		"success" => mysql_errno() == 0
	));
?>