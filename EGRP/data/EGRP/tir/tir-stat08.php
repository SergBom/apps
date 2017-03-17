<?php
/**************************************************************
 Статистика Вариант3
*/
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query2.php");
header('Content-type: text/html; charset=utf-8');

/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
/****************************************************************************/

$conn = ConnectLocalTIR(); // Присоска к базе

$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat31b']);

//echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


$CalcErN = 0;
$CalcEr1 = 0;
$CalcEr2 = 0;
$CalcEr3 = 0;
$CalcTotal = 0;
$C=0;
$data = array();

while (($row = oci_fetch_array($stid))){
	$CalcErN += $row['ERN'];
//	$CalcErN0 += $CalcErN + $CalcEr0;
	$CalcEr1 += $row['ER1'];
	$CalcEr2 += $row['ER2'];
	$CalcEr3 += $row['ER3'];
	$CalcTotal += $row['TOTAL'];
	$C++;
	array_push($data, array(
		'id'=>$C,
		'name'=>"{$row['NAME']}",
		'ern'=>"{$row['ERN']}",
//		'er0'=>"{$row['ER0']}",
		'er1'=>"{$row['ER1']}",
		'er2'=>"{$row['ER2']}",
		'er3'=>"{$row['ER3']}",
		'calc'=>$row['TOTAL']
	));
}	

	array_push($data, array(
		'id'=>$C+1,
		'name'=>"<b>Всего:</b>",
		'ern'=>"<b>{$CalcErN}</b>",
//		'er0'=>"<b>{$CalcEr0}</b>",
		'er1'=>"<b>{$CalcEr1}</b>",
		'er2'=>"<b>{$CalcEr2}</b>",
		'er3'=>"<b>{$CalcEr3}</b>",
		'calc'=>"<b>$CalcTotal</b>"
	));

		echo json_encode(array(
				"success"=>"true",
				"data"=>$data
			));		
?>