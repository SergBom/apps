<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/

	$data=array();
	array_push($data,[	'id'=> 0,  'name'=> 'Не обработанные' ]);
	array_push($data,[	'id'=> 1,  'name'=> 'В обработке' ]);
	array_push($data,[	'id'=> 2,  'name'=> 'Завершенные' ]);
	array_push($data,[	'id'=> 8,  'name'=> 'Все' ]);
		
	$c = array('success'=>0,'data'=>$data );
	echo json_encode($c);
	
?>