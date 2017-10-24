<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	//$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/


	$sql = "SELECT * FROM `A_Tables`";
	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
/*
	$sql = "show table status where substr(name,1,2)='tb'";
	$st = $db->query( $sql );
	
	while( $row = $st->fetch() ){

		$cmt = @explode('|',$row['Comment']);

		$data[] = array(
			'id'=>$row['Name'],
			'name'=>$row['Name'],
			'title'=>@$cmt[0],
			'info'=>@$cmt[1]
		);
	}
	*/
	
	echo json_encode(array('success'=>'true','data'=>$data));

?>