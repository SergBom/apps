<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$id = $_POST['id'];

/*	$data = json_decode($info);
	$id = $data->id;

	/* Проверка связанных записей
	*/
	
	$sql = "SELECT count(*) cnt FROM address WHERE city_id=$id";
	$cnt = $db->getOne( $sql );
	if ( $cnt == 0 ){ // Нет записей - Удаляем
		
		$db->query( "DELETE FROM city WHERE id=$id" );

		echo json_encode(array(
			"success" => mysql_errno() == 0
		));
		
	} else {  // Есть записи - в Ошибку
		echo json_encode(array(
			'success'=>false,
			'msg'=>'Удаление невозможно!<br>Данный населенный пункт используется в базе.')
		);
	}
?>