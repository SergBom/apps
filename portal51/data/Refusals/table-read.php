<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_gzn');
/*---------------------------------------------------------------------------*/

$w[0]='Нет';
$w[1]='Да';
$data = array();

	$filter = isset($_GET['filter']) ? " WHERE cad_num like '%{$_GET['filter']}%' OR address like '%{$_GET['filter']}%' " : "";

	//$sql = "SELECT * FROM address ORDER BY name";
	
	$sql = "select *
			from `v#refusals`
			$filter
			order by dateUpdate desc";
	
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			$row['dateUpdate2'] = substr($row['dateUpdate'],0,10);
			$row['itsOk2']      = $w[ $row['itsOk'] ];
			array_push($data, $row);
			
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>