<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('ETables');

/*---------------------------------------------------------------------------*/

$a_type = array(
	'VARCHAR'=>"'string'",
	'DATE'=>"'date'",
	'TIME'=>"'date'",
	'INT'=>"'int'",
	'FLOAT'=>"'float'"
);



	$sql = "SELECT * FROM `S_Tables` WHERE a_id={$a->main_id} ORDER BY field_order";
	$st = $db->query( $sql ); //->fetchAll(); //PDO::FETCH_COLUMN);
	

	
/*var rec = new Portal.model.ETables.TblFileds({
  id:0,
  
});
	
	*/
		
	$s = "var rec = new Portal.model.ETables.TblFileds({";
	$a = array();
	while( $row = $st->fetch() ){
		
		$a1 = "{$row['field_name']}:"; 

		switch ($row['field_type']){
			case 'INT':$a2 = "0";
			break;
			case 'FLOAT':$a2 = "0";
			break;
			case 'DATE':$a2 = "'". date("Y-m-d") . "'";
			break;
			case 'TIME':$a2 = "'00:00'";
			break;
			case 'VARCHAR':$a2 = "''";
			break;
		}
		
		$a[] = $a1 . $a2;
		
	}
	
	$s = $s . implode(",",$a) . "});";
	
	
	echo json_encode(array('success'=>'true','data'=>$s));
?>
