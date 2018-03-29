<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_POST; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('DisputCadastralValue');

/*---------------------------------------------------------------------------*/
	$sql = "DELETE FROM `{$a->t}` WHERE id={$a->id}";
	//echo $sql;
	$db->query( $sql );

	echo json_encode(array('success'=>'true'));
?>