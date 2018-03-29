<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($method);

switch ($method) {
  case 'GET':  // SELECT
	_get_data($db,$params);
    break;
}

function _get_data($db,$params){	// Вывести список записей
$data = array();	

	$sql = "SELECT * FROM t\$district_data ORDER BY n1"; //WHERE Org_ID='.$Org_id.'
	if ( $result = $db->query( $sql ) ) {
//		$rows = array();
//		$cnt = 0;

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
//			$cnt++;
		}

		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
	}
}
?>