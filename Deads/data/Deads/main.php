<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$FIO = isset($_GET['FIO']) ? "FIO like '%". $_GET['FIO'] ."%'" : "0=0";
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('Deads');

/*---------------------------------------------------------------------------*/
	$page  = $_GET['page'];
	$start = $_GET['start'];
	$limit = $_GET['limit'];


	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `main` WHERE $FIO ORDER BY FIO LIMIT $start, $limit  ";
	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	
	$total_rows=$db->query("select FOUND_ROWS()")->fetchAll(PDO::FETCH_COLUMN);
	
	echo json_encode(array('success'=>'true','total'=>$total_rows,'data'=>$data));

?>