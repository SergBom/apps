<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$info = $_POST['data'];

	$data = json_decode($info);

	$id = $data->id;

	/* Проверка связанных записей
	*/
	
//	$sql = "SELECT count(*) cnt FROM planer WHERE type_tour=$id";
//	$cnt = $db->getOne( $sql );
//	if ( $cnt == 0 ){ // Нет записей - Удаляем
		
		$db->query( "DELETE FROM tours WHERE id=$id" );

		echo json_encode(array(
			"success" => mysql_errno() == 0
		));
		
//	} else {  // Есть записи - в Ошибку
//		echo json_encode(array(
//			'success'=>false,
//			'data'=>'Ошибка')
//		);
//	}
?>