<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_POST; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('DisputCadastralValue');

/*---------------------------------------------------------------------------*/
$u=array();
foreach( $_POST as $k => $v){
	
	if($k <> 'id' AND substr($k,0,1) <> '_'){
		array_push($u,"$k='$v'");
		//echo "'$k'=>'$v'<br>";	
	}
	
}
$su = implode(',',$u);



if($a->id <> 0){ // Edit
	$sql = "UPDATE `{$a->_t}` SET {$su} WHERE id={$a->id}";
}else{ // Add
	$sql = "INSERT `{$a->_t}` SET {$su}";
}

	echo $sql;
	$db->query( $sql );

/*

	$sql = "SELECT * FROM `{$a->table}`";
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}
*/
	//echo json_encode(array('success'=>'true','data'=>$_POST));
?>