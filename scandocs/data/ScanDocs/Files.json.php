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

//$params = parseRequest($method);

switch ($method) {
  case 'GET':  // SELECT
	_get_data($db,$_GET);
    break;
  case 'POST':  // SELECT
	_get_data($db,$_POST);
    break;
}


function _get_data($db,$params){	// Вывести список записей
$data = array();	

//print_r($params);

	$a = explode('/',$_SERVER["DOCUMENT_ROOT"]);
//print_r($a);
	
	if( $a[1] == 'var'){
		$rp = "/mnt/archiveshare/scan_docs/";
	} else {
		$rp = 'file://///ARCHIVESHARE/Archive/scan_docs/';
	}

	$dir = ( @$params['dir'] ) ? @$params['dir'] : "''";
	
	
	$sql = "SELECT c.id, c.tp, IFNULL(round(c.cnt_size/(1024),2),0) cnt_size,
		c.cdate, c.cyear, concat(\"[\",p1.name,\"] - \",c.name) as files,
		concat(c.path,'/',c.cyear,'/$dir/',p1.name,'/',c.name) as fname,
		right(c.name,3) as ext
		FROM docs2 AS c
		LEFT JOIN docs2 As p1 ON c.par_id = p1.id
		LEFT JOIN `v#docs2` As p2 ON p1.par_id = p2.id
		WHERE c.par_id IS NOT NULL AND right(c.name,3)='pdf' AND p2.tp=1 AND p2.name='$dir'";
	$sql .= " ORDER BY files";
	
	//echo $sql."<br>";
	
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			//$row['fname'] = $rp . $row['fname'];
			array_push($data, $row);
		}
		$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
	}
}
?>