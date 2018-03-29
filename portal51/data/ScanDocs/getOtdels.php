<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

//$data = array();	

	$sql = "SELECT * FROM `otdels` ORDER BY id"; 
	$data = $db->query( $sql )->fetchAll();

	echo json_encode(array('success'=>0,'data'=>$data));

?>