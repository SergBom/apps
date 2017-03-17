<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // Период выгрузки
/*---------------------------------------------------------------------------*/

include_once("tir-query2.php");
//echo date("H:i:s")."<br>";
	$conn = ConnectLocalTIR(); // Присоска к базе
//echo date("H:i:s")."<br>";

	$stid = oci_parse($conn, $sqlLOC['sessionLIST']);
	oci_execute($stid);
	while (($row = oci_fetch_array($stid))){	
	
		//$rows = array();
		

		if($row['STATUS']=='_'){
		$data[] = array(
			'session'=>$row['SESSION_ROOT'],
			'periodV'=>$row['VYGRUZKA#'],
			'periodS'=>$row['STATUS'],
			'period'=>"<b>{$row['VYGRUZKA#']}{$row['STATUS']} ({$row['DATE_BEGIN']} - {$row['DATE_END']}) ({$row['DOC_CNT']}={$row['FLK_ERR']}={$row['FNS_ERR']})</b>");
		
		} else {
		$data[] = array(
			'session'=>$row['SESSION_ROOT'],
			'periodV'=>$row['VYGRUZKA#'],
			'periodS'=>$row['STATUS'],
			'period'=>"{$row['VYGRUZKA#']}{$row['STATUS']} ({$row['DATE_BEGIN']} - {$row['DATE_END']}) ({$row['DOC_CNT']}={$row['FLK_ERR']}={$row['FNS_ERR']})");
		}
		
		
	}

	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);
//	SELECT($conn);
	
//@oci_free_statement($stid);
//@oci_close($conn);
/*******************************************************/
function SELECT($conn){


}

//echo date("H:i:s")."<br>";

?>