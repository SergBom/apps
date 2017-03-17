<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/

	$db->query( "UPDATE docs2 SET retro=1 WHERE id={$_POST['id']}" );

	$c = array('success'=>0); //,'file'=>'data/ScanDocs/temp/'.$temp_URL,'folder'=>$DPD);
	echo json_encode($c);
?>