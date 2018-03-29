<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

/*	$data = array();
	$info = $_POST['data'];
	$adata = json_decode($info); */
	$adata = (object)$_POST;
	//print_r($adata);
	
	if($adata->id==0){ // Добавляем
		$db->query("INSERT INTO `portal`.`tabMainNews` SET
			news='{$adata->news}',
			subject='{$adata->subject}',
			userInsert='{$_SESSION['portal']['user_id']}'");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"news" => $adata->news,
				"subject" => $adata->subject,
				"userInsert" => $_SESSION['portal']['FIO']
			)
		));
		
	} else { // Исправляем
		$db->query("UPDATE  `portal`.`tabMainNews` SET
			news='{$adata->news}',
			subject='{$adata->subject}',
			userUpdate='{$_SESSION['portal']['user_id']}' WHERE id={$adata->id}");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"news" => $adata->news,
				"subject" => $adata->subject,
				"userUpdate" => $_SESSION['portal']['FIO']
			)
		));
	}
?>