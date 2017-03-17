<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/

	$data = array();
	
	$sql = "SELECT DISTINCT cyear FROM docs2 ORDER BY cyear";
	if ( $result = $db->query( $sql ) ) {

		array_push($data, array("cyear"=>"*"));

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		echo json_encode(array('success'=>'true','data'=>$data));
	}
?>