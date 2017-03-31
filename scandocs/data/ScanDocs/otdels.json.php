<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

$data = array();	

	$sql = "SELECT DISTINCT n1 id, n2 as name FROM t\$district_data ORDER BY n1"; //WHERE Org_ID='.$Org_id.'
	
	//$sql = "SELECT * FROM otdels ORDER BY id";
	
	if ( $result = $db->query( $sql ) ) {
		array_push($data, ['id'=>"",'name'=>""] );
		
		while ($row = $result->fetch()) {
			array_push($data, $row);
		}

		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
	}

?>