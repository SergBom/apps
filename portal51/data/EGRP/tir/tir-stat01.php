<?php
/**************************************************************
 Статистика общая
*/

include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query.php");
header('Content-type: text/html; charset=utf-8');

/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
/****************************************************************************/

$conn = ConnectLocalTIR(); // Присоска к базе

$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat_common']);

//echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


$row = oci_fetch_array($stid);


// Документов к выгрузке	Документов не прошло выходной ФЛК	Объектов выгружено	% прохождения выходного ФЛК
						
		$data[]=array(
			'id'=>1,
			'name'=>'<b>Земельные участки:</b>',
			'd1'=>$row['CNT_ERTH'],
			'd2'=>$row['CNT_BAD_ERTH'],
			'd3'=>$row['CNT_NICE_ERTH'],
			'perc'=>round($row['CNT_NICE_ERTH'] * 100 / $row['CNT_ERTH'],2)
		);				

		$data[]=array(
			'id'=>2,
			'name'=>'<b>Здания:</b>',
			'd1'=>$row['CNT_HOME'],
			'd2'=>$row['CNT_BAD_HOME'],
			'd3'=>$row['CNT_NICE_HOME'],
			'perc'=>round($row['CNT_NICE_HOME'] * 100 / $row['CNT_HOME'],2)
		);				

		$data[]=array(
			'id'=>3,
			'name'=>'<b>Помещения:</b>',
			'd1'=>$row['CNT_APTM'],
			'd2'=>$row['CNT_BAD_APTM'],
			'd3'=>$row['CNT_NICE_APTM'],
			'perc'=>round($row['CNT_NICE_APTM'] * 100 / $row['CNT_APTM'],2)
		);				

		$data[]=array(
			'id'=>4,
			'name'=>'<b>Всего:</b>',
			'd1'=>'<b>'.$row['TOTAL_DOC'].'</b>',
			'd2'=>'<b>'.$row['CNT_BAD'].'</b>',
			'd3'=>'<b>'.$row['CNT_NICE'].'</b>',
			'perc'=>'<b><font color="red">'.round($row['CNT_NICE'] * 100 / $row['TOTAL_DOC'],2).'</font></b>'
		);				
		
		echo json_encode(array(
				"success"=>"true",
				"data"=>$data
			));		
						

?>