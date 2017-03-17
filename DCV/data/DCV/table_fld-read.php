<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

   	$db = ConnectPDO('DCVm');

/*---------------------------------------------------------------------------*/




	$sql = "SHOW COLUMNS FROM `{$a->table}`";
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch()) {
			
			$d['dataIndex']=$row['Field'];
			$d['name']=$row['Field'];
			$d['header']=$row['Field'];
			$d['tooltip']=$row['Field'];
			$d['xtype']=transType($row['Type']);
			
			if($row['Field']=='id'){
				$d['width']=30;
				$d['flex']="";
			//} else if($row['Field']=='name'){
			//	$d['width']="";
			//	$d['flex']=1;
			} else {
				$d['width']="";
				$d['flex']=1;
			}
			array_push($data,$d);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}
	
/*
dataIndex: record.get('dataIndex'),
header: record.get('header'),
tooltip: record.get('tooltip'),
name: record.get('name'),
xtype: record.get('xtype'),
*/
function transType($t){
	$xtype = array(
	'int'=>'int',
	'date'=>'date',
	'real'=>'float',
	'varchar'=>'string',
	'tinyint'=>'boolean'
	);	
	
	preg_match('/^\w+/i',$t,$matches);
	//print_r($matches);
	return $xtype[$matches[0]];
}
?>