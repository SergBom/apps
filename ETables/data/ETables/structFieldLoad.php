<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/




	$sql = "SELECT * FROM `S_Tables` WHERE a_id={$a->main_id} AND field_name<>'id' ORDER BY field_order";
	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	
/*	
	$sql = "show full columns from {$a->main_id}";
	$st = $db->query( $sql );

	$c=1;
	while( $row = $st->fetch() ){

		$cmt = @explode('|',$row['Comment']);

		$data[] = array(
			'id'=>'f'.$c,
			'field_name'=>$row['Name'],
			'field_title'=>@$cmt[0],
			'field_type'=>@$cmt[1]
			'field_len'=>@$cmt[1]
			'field_PK'=>@$cmt[1]
			'field_INDEX'=>@$cmt[1]
			'field_order'=>@$cmt[1]
			
		);
	}
*/
	
	
	echo json_encode(array('success'=>'true','data'=>$data));
?>
