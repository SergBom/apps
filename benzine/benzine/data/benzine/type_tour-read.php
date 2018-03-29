<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/


	$data = array();
	
	$filter = '';
/*	if( isset($_GET['query']) )	{
		$filter = "WHERE  lower(name) LIKE '%".mb_convert_case($_GET['query'], MB_CASE_LOWER, "UTF-8")."%'";
	} */
	$sql = "SELECT * FROM type_tours $filter ORDER BY name";
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>