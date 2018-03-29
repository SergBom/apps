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

//$result = $db->query( "SELECT * FROM `refs` ORDER By reference" );
$result = $db->query( "SHOW TABLES LIKE 'ref_%'" );
while ($row = $result->fetch_array()) {
	
	echo $row[0]."<br>";
	$res2 = $db->query( "SELECT * FROM `{$row[0]}`" );
	while ($row2 = $res2->fetch_array()) {
		echo $row[0]."<br>";
	}
	SELECT table_name,Engine,Version,Row_format,table_rows,Avg_row_length,
Data_length,Max_data_length,Index_length,Data_free,Auto_increment,
Create_time,Update_time,Check_time,table_collation,Checksum,
Create_options,table_comment FROM information_schema.tables
WHERE table_schema = 'mysql'
	
	
	
}	


/*
if($a->_e == 'e'){ // Edit
	$sql = "UPDATE `{$a->_t}` SET {$su} WHERE id={$a->id}";
}else{ // Add
	$sql = "INSERT `{$a->_t}` SET {$su} WHERE id={$a->id}";
}

	//echo $sql;
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