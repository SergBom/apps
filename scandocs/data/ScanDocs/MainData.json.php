<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/


	$dsn= "mysql:host=10.51.119.244;dbname=Scan_docs;charset=utf8";
	$opt = array(
		PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
	);
	$db = new PDO($dsn, 'root', 'javascript', $opt);
	
	
	
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

	$limit=$params['limit'];
	$page =$params['page'];
	$start=$params['start'];

	$begin = ( @$params['dateBegin'] ) ? " AND p2.cdate >= str_to_date('".str_replace('T00:00:00','',@$params['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( @$params['dateEnd'] )   ? " AND p2.cdate <= str_to_date('".str_replace('T00:00:00','',@$params['dateEnd'])."', '%Y-%m-%d') " : "";
	$cyear = ( @is_numeric($params['cyear']) )     ? " AND p2.cyear = {$params['cyear']}" : "";
	$otdel = ( @$params['Otdel'] )     ? " AND p2.n1=".@$params['Otdel']." " : "";
	$filter = ( @$params['filter'] )     ? " AND name like '%".trim(@$params['filter'])."%' " : "";
	
/*	if( $filter ){
		$limit=25;
		$page =1;
		$start=0;
	} else {
		$limit=$params['limit'];
		$page =$params['page'];
		$start=$params['start'];
	}
	*/	
	
	
	
	$opis = ""; 
	if( $params['opis']<8 and $params['opis']<>'' ){
		$opis = " AND opis=".$params['opis'] ;
	}
	
	$retro = ( $params['retro']<>'' and $params['retro']<8 ) ? " AND retro=".$params['retro'] : "" ;
	
	
	$sql = "select SQL_CALC_FOUND_ROWS * from `v_docs3` p2 where 0=0 $begin  $end  $otdel $cyear $opis $retro $filter";
	$sql .= " ORDER BY p2.n1, p2.name";
	$sql .= " LIMIT $start,$limit";
	
	//echo $sql;

	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	$total_rows=$db->query("select FOUND_ROWS()")->fetchAll(PDO::FETCH_COLUMN);
	
	
	$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
	echo json_encode($c);
	
}
?>