<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('FSSP');

/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($method);

switch ($method) {
  case 'GET':  // SELECT
	_get_data($db,$params);
    break;
  case 'POST':  // SELECT
	_get_data($db,$_POST);
	break;
}

function _get_data($db,$params){	// Вывести список записей
$data = array();	
//print_r($params);
	
	$begin = " date >= str_to_date('".substr($params['dateBegin'],0,10)."', '%Y-%m-%d') ";
	$end   = " date <= str_to_date('".substr($params['dateEnd'],0,10)."', '%Y-%m-%d') ";
	$otdel = ( @$params['Otdel'] ) ? " AND otdel = ".@$params['Otdel']." " : "";

	
	$sql = "SELECT sum(doc_in) as DocsInput, sum(doc_reg) as RegsInput,
			sum(doc_double) as DoubleInput, sum(doc_tech) as TechInput, sum(doc_otkaz) as OtkazInput, sum(doc_stop) as StopInput
			FROM fssp WHERE $begin AND $end $otdel";
	
	//echo $sql;
	
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			echo "{
				success: true,
				data: {
					DocsInput: ".intval($row['DocsInput']).",
					RegsInput: ".intval($row['RegsInput']).",
					DoubleInput: ".intval($row['DoubleInput']).",
					TechInput: ".intval($row['TechInput']).",
					OtkazInput: ".intval($row['OtkazInput']).",
					StopInput: ".intval($row['StopInput'])."
				}
			}";
		}

	} else {
		echo json_encode(array('success'=>false));
	}
}
?>