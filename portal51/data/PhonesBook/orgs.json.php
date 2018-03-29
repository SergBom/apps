<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- ¬ходные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

//global $_SB_cfg, $_dbLocal;
	
/*---------------------------------------------------------------------------*/

	$data = array();
	
	$sql = "SELECT * FROM `prt#orgs`"; // ORDER BY code"; //WHERE Org_ID='.$Org_id.'
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		echo json_encode(array('success'=>'true','data'=>$data));
	}
?>