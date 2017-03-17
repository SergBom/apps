<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

//    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/
$data = array();	

	

		array_push($data, array('id'=>1,'name'=>'Ошибки ФЛК','className'=>'egrp.flk'));
		array_push($data, array('id'=>2,'name'=>'Ошибки ФНС','className'=>'egrp.fns'));
		array_push($data, array('id'=>3,'name'=>'Статистика','className'=>'egrp.stat'));
		
		
		
		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
?>