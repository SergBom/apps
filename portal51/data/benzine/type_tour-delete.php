<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

/*	if (isset($_POST['data'])){
		$data = json_decode($_POST['data']);
		$id = $data->id;
	} else {*/
		$id = $_POST['id'];
	//}
		
	/* Проверка связанных записей
	*/
	
	$sql = "SELECT count(*) cnt FROM planer WHERE type_tour=$id";
	$cnt = $db->getOne( $sql );
	if ( $cnt == 0 ){ // Нет записей - Удаляем
		
		$sql = "DELETE FROM type_tours WHERE id=$id";
		$db->query( $sql );

		echo json_encode(array(
			"success" => mysql_errno() == 0
		));
		
	} else {  // Есть записи - в Ошибку
		echo json_encode(array(
			'success'=>false,
			'msg'=>'Удаление невозможно!<br>Данная запись используется в базе.')
		);
	}
?>