<?php
//include_once("../../../php/init.php");
header('Content-type: text/html; charset=utf-8');

		$data[] = array( 'id'=>'0',	'name'=>'Для сайта');
		$data[] = array( 'id'=>'1',	'name'=>'Расширенный 1');
//		$data[] = array( 'id'=>'2',	'name'=>'Расширенный 2');
 
	echo json_encode(Array(
		"success"=>"true",
		"data"=>$data
	));		

?>