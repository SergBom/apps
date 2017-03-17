<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

//	$data = array();
	//$info = $_POST['data'];
	$adata = (object)$_POST; //json_decode($info);

	$sql = "select IFNULL(max(distance),0) distance from tours where
			( ( tour_from = '{$adata->tour_from}' and tour_to = '{$adata->tour_to}')
			or
			( tour_from = '{$adata->tour_to}' and tour_to = '{$adata->tour_from}') )
	";
	//echo $sql;
	$distance = $db->getOne( $sql );


	echo json_encode(array('success'=>true,'data'=>array("distance"=>$distance)));

?>