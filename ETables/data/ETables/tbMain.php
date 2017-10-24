<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);

	$table_name = 'tb' . str_pad($a->id,8,'0',STR_PAD_LEFT);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/


	$sql = "SELECT field_name FROM `S_Tables` WHERE a_id={$a->id} AND field_order>=0";
	$ff = $db->query( $sql )->fetchAll(PDO::FETCH_COLUMN);
	$f = implode(',',$ff);


	$sql = "SELECT $f FROM `{$table_name}`";
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
