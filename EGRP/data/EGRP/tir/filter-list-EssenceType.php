<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // Период выгрузки
/*---------------------------------------------------------------------------*/

	//$conn = ConnectLocalTIR(); // Присоска к базе

	//$stid = oci_parse($conn, "");
	//oci_execute($stid);
	//while (($row = oci_fetch_array($stid))){	
	

		$data[] = array(
			'session'=>$row['SESSION_ROOT'],
			'period'=>"{$row['VYGRUZKA#']}{$row['STATUS']} ({$row['DATE_BEGIN']} - {$row['DATE_END']}) ({$row['DOC_CNT']}={$row['FLK_ERR']}={$row['FNS_ERR']})");


	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);
//	SELECT($conn);
	
//@oci_free_statement($stid);
//@oci_close($conn);
/*******************************************************/
?>