<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	//$_SESSION['portal']['user_id']
	//$_SESSION['portal']['username']
	//$_SESSION['portal']['FIO']
	//$_SESSION['portal']['otdel_id']

	$data = array();
	$a = (object)$_REQUEST; //json_decode($info);

	//print_r($a);
	$id=$a->id;
	
	$out = "";
	
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('HW_PC');
/*---------------------------------------------------------------------------*/


	$header = $db->query( "SHOW COLUMNS FROM `Processor`" )->fetchAll(PDO::FETCH_COLUMN,0);
	$header = array_slice($header,2);

	$sql = "select * from `Processor` where main_id=$id"; // $filter";
	if ( $result = $db->query( $sql ) ) {
		$out .= "<div class='hd01'>Микропроцессор:</div><table border='1' cellspacing='0' cellpadding='0' class='tbl-001'><tbody>";
		$out .= "<tr class='tbl-hd1'><td>".implode("</td><td>",$header)."</td></tr>";
		
		while ($row = $result->fetch()) {
			$row = array_slice($row,2);
			$out .= "<tr><td>".	implode("</td><td>",$row)."</td></tr>";
		}
		$out .= "</tbody></table><br>";
	}

	
	$header = $db->query( "SHOW COLUMNS FROM `v_PhysicalMemory`" )->fetchAll(PDO::FETCH_COLUMN,0);
	$header = array_slice($header,2);
	
	$sql = "select * from `v_PhysicalMemory` where main_id=$id"; // $filter";
	if ( $result = $db->query( $sql ) ) {
		$out .= "<div class='hd02'>Физическая память:</div><table border='1' cellspacing='0' cellpadding='0' class='tbl-002'><tbody>";
		$out .= "<tr class='tbl-hd1'><td>".implode("</td><td>",$header)."</td></tr>";
		
		while ($row = $result->fetch()) {
			$row = array_slice($row,2);
			$out .= "<tr><td>".	implode("</td><td>",$row)."</td></tr>";
		}
		$out .= "</tbody></table><br>";
	}


	$header = $db->query( "SHOW COLUMNS FROM `v_DiskDrive`" )->fetchAll(PDO::FETCH_COLUMN,0);
	$header = array_slice($header,2);
	
	$sql = "select * from `v_DiskDrive` where main_id=$id"; // $filter";
	if ( $result = $db->query( $sql ) ) {
		$out .= "<div class='hd02'>Жесткие диски:</div><table border='1' cellspacing='0' cellpadding='0' class='tbl-002'><tbody>";
		$out .= "<tr class='tbl-hd1'><td>".implode("</td><td>",$header)."</td></tr>";
		
		while ($row = $result->fetch()) {
			$row = array_slice($row,2);
			$out .= "<tr><td>".	implode("</td><td>",$row)."</td></tr>";
		}
		$out .= "</tbody></table><br>";
	}






	echo $out; 
	
?>