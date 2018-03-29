<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('Scan_docs');

	$Odb = ConnectOciDB('EGRP');
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


	$limit=$params['limit'];
	$page =$params['page'];
	$start=$params['start'];

//print_r($params);

	/*$begin = ( @$params['dateBegin'] ) ? " cdate >= str_to_date('".str_replace('T00:00:00','',@$params['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( @$params['dateEnd'] )   ? " cdate <= str_to_date('".str_replace('T00:00:00','',@$params['dateEnd'])."', '%Y-%m-%d') " : "";
	$cyear = ( @$params['cyear'] )     ? " AND p2.cyear = {$params['cyear']}" : "";
	$otdel = ( @$params['Otdel'] )     ? " n1 = ".@$params['Otdel']." " : "";
*/

	$begin = ( @$params['dateBegin'] ) ? " AND p2.cdate >= str_to_date('".str_replace('T00:00:00','',@$params['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( @$params['dateEnd'] )   ? " AND p2.cdate <= str_to_date('".str_replace('T00:00:00','',@$params['dateEnd'])."', '%Y-%m-%d') " : "";
	$cyear = ( @is_numeric($params['cyear']) )     ? " AND p2.cyear = {$params['cyear']}" : "";
	$otdel = ( @$params['Otdel'] )     ? " AND p2.n1 = ".@$params['Otdel']." " : "";
	$filter = ( @$params['filter'] )     ? " AND name like '%".trim(@$params['filter'])."%' " : "";
	
	
	//$sql = "select * from `v#docs2` p2 where p2.tp=1 $begin  $end  $otdel $cyear $filter";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS  * FROM docs_l1 AS c
		WHERE 1=1
			AND name not regexp '51 [0-9][0-9] [0-9][0-9][0-9][0-9][0-9][0-9][0-9] [0-9].*'";
	
	$sql .= " ORDER BY name";
	$sql .= " LIMIT $start,$limit";
	
	//echo $sql."<br>";
	
	
	//$sql = "SELECT * FROM v\$cad_nums ORDER BY n2"; //WHERE Org_ID='.$Org_id.'
	if ( $result = $db->query( $sql ) ) {

		$total_rows=$db->getOne("select FOUND_ROWS()");
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		
		
		
		$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
		echo json_encode($c);
	}
}
?>