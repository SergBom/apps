<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');



	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	//$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	//$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';




$db = ConnectPDO('ETables');

$j = json_decode($_REQUEST['data']);
/**********
	Приходят данные:

	{
		"id":15,
		"a_id":"2",
		"field_name":"fld54307",
		"field_title":"\u041f\u043e\u043b\u04353\u0444",
		"field_type":"VARCHAR",
		"field_len":23,
		"field_PK":"0",
		"field_INDEX":"0",
		"field_order":0,
		"xtype":"gridcolumn",
		"editor":null,
		"allowBlank":null
	}

*/

//var_dump($j);

$TableName  = 'tb' . str_pad($j->a_id,8,'0',STR_PAD_LEFT);

$fld=array();
foreach( $j as $k => $v ){
	if($k <> 'id' AND $k <> 'a_id' ){
		array_push($fld, "`$k`='$v'");
	}
}

$set = implode(',',$fld);

if ( $j->id == 0 ){
	
	$sql = "INSERT INTO $TableName SET $set "; 
	
} else {
	
	$sql = "UPDATE $TableName SET $set WHERE id={$j->id}"; 

}
	
	//echo "$sql<br>";

	$db->query($sql);

echo json_encode(array('success'=>'true','sql'=>$sql,'data'=>$j));
?>