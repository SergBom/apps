<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init2.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/


    $db = ConnectPDO('Scan_docs');
	
	$data = $db->query( "select * from v_years" )->fetchAll(); //PDO::FETCH_COLUMN);
	
	echo json_encode(array('success'=>'true','data'=>$data));
	
?>