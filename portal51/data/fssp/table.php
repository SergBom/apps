<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('FSSP');

/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($method);

switch ($method) {
  case 'GET':  // SELECT
	_get_data($db,$_GET);
    break;
}

function _get_data($db,$params){	// Вывести список записей
$data = array();	

	
	$begin = " date >= str_to_date('".substr($params['dateBegin'],0,10)."', '%Y-%m-%d') ";
	$end   = " date <= str_to_date('".substr($params['dateEnd'],0,10)."', '%Y-%m-%d') ";
	$otdel = ( @$params['Otdel'] ) ? " AND otdel = ".@$params['Otdel']." " : "";

	
	$sql = "SELECT t1.*, t2.otdel_name  FROM fssp t1 left join 
			(SELECT DISTINCT n1 as id, concat('{',n1,'} ',n2) as otdel_name FROM portal.t\$district_data) t2 on t1.otdel=t2.id
			WHERE $begin AND $end $otdel";
	
	//echo $sql;
	
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
	}
	
}
?>