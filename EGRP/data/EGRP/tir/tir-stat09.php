<?php
/**************************************************************
 Статистика для Вариант4 (R-ЕГРП) (C-ГКН)
*/
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query2.php");
header('Content-type: text/html; charset=utf-8');

/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "" ); // По умолчанию Для налоговой, иначе для систем (R-ЕГРП) (C-ГКН)
/****************************************************************************/

$conn = ConnectLocalTIR(); // Присоска к базе

	// Вытаскиваем и формируем запрос из базы 
	$sql = 'SELECT FLK_ERR FROM T$SESSION WHERE SESSION_ROOT='.$TIR_Session; //str_replace(':SESSION',$TIR_Session,$sqlLOC['stat33']);
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);
	$r = oci_fetch_array($stid);
	$err_flk = $row['FLK_ERR'];

	$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat33a']);
	$sql = str_replace(':ST',$TIR_System,$sql);
//echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


//	$cLocalTIR = ConnectLocalTIR();

$CalcErRF = 0;
$CalcErN = 0;
$CalcEr1 = 0;
$CalcEr2 = 0;
$CalcEr3 = 0;
$CalcTotal = 0;
$CalcPispr = 0;
$CalcPobr = 0;
$C=0;
$data = array();

while (($row = oci_fetch_array($stid))){
	$CalcErRF += $row['ERF'];
	$CalcErN += $row['ERN'];
	$CalcEr1 += $row['ER1'];
	$CalcEr2 += $row['ER2'];
	$CalcEr3 += $row['ER3'];
	$CalcTotal += $row['TOTAL'];
	$CalcPispr += $row['PISPR'];
	$CalcPobr  += $row['POBR'];
	$C++;
	
	array_push($data, array(
		'id'=>$C,
		'name'=>"{$row['NAME']}",
		'erf'=>"{$row['ERF']}",
		'ern'=>"{$row['ERN']}",
		'er1'=>"{$row['ER1']}",
		'er2'=>"{$row['ER2']}",
		'er3'=>"{$row['ER3']}",
		'calc'=>$row['TOTAL'],
		'pispr'=>$row['PISPR'],
		'pobr'=>$row['POBR']
	));

}	

	array_push($data, array(
		'id'=>$C+1,
		'name'=>"<b>Всего:</b>",
		'erf'=>"<b>{$CalcErRF}</b>",
		'ern'=>"<b>{$CalcErN}</b>",
		'er1'=>"<b>{$CalcEr1}</b>",
		'er2'=>"<b>{$CalcEr2}</b>",
		'er3'=>"<b>{$CalcEr3}</b>",
		'calc'=>"<b>{$CalcTotal}/{$err_flk}</b>",
		'pispr'=>"",
		'pobr'=>""
	));

		echo json_encode(array(
				"success"=>"true",
				"data"=>$data
			));		
?>