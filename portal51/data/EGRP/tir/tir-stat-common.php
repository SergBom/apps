<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-connect-tir.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-connect-loc.php");
include_once("tir-query.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/function.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/xml-header.php");
header('Content-type: text/html; charset=utf-8');


$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "R" ); // По умолчанию - ЕГРП (C- ГКН)
$V = trim ((!empty($_GET['V'])) ? $_GET['V'] : "" ); // Номер выгрузки
$S = trim ((!empty($_GET['S'])) ? $_GET['S'] : "" ); // Номер выгрузки 2
$Period = trim ((!empty($_GET['Period'])) ? $_GET['Period'] : "" ); // Период выгрузки

$conn = ConnectLocalTIR(); // Присоска к базе



$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat_common']);
//$stid = sql_exec($connLOC, $sql);

//echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


$row = oci_fetch_array($stid);

//	$row['SESSION_ROOT']
// $Period = $row['DATE_BEGIN']." - ".$row['DATE_END'];
/*
$row['CNT_ERTH'] Объектов к выгрузке в т.ч. земельных участков
$row['CNT_HOME'] Объектов к выгрузке в т.ч. зданий
$row['CNT_APTM'] Объектов к выгрузке в т.ч. помещений
	$row['TOTAL_DOC'] Объектов к выгрузке Всего
						$row['CNT_BAD_ERTH']Объектов не прошло выходной ФЛК в т.ч. земельных участков
						$row['CNT_BAD_HOME']Объектов не прошло выходной ФЛК в т.ч. зданий
						$row['CNT_BAD_APTM']Объектов не прошло выходной ФЛК в т.ч. помещений
						$row['CNT_NICE_ERTH']Объектов выгружено в т.ч. земельных участков
						$row['CNT_NICE_HOME']Объектов выгружено в т.ч. зданий
						$row['CNT_NICE_APTM']Объектов выгружено в т.ч. помещений
						$row['CNT_NICE']Объектов выгружено Всего
						$row['CNT_BAD']Объектов не прошло выходной ФЛК Всего
						$row['TOTAL']Количество ошибок выходного ФЛК
						$row['CNT_ERR_ERTH']Количество ошибок выходного ФЛК в т.ч. по земельным участкам
						$row['CNT_ERR_HOME']Количество ошибок выходного ФЛК в т.ч. по зданиям
						$row['CNT_ERR_APTM']Количество ошибок выходного ФЛК в т.ч. по помещениям
						$row['DOCUMENT_RES_LOG']Количество ошибок входного ФЛК
						$row['RES_DOC']Количество ошибочных документов входного ФЛК
*/

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
		
		sleep(15);
		echo json_encode(array(
				"success"=>"true",
				"data"=>$data
			));		
						
/*
<rows>
	<row id="1">
		<cell><![CDATA[ <b>Земельные участки:</b> ]]></cell>
		<cell><?php echo $row['CNT_ERTH']?></cell>
		<cell><?php echo $row['CNT_BAD_ERTH']?></cell>
		<cell><?php echo $row['CNT_NICE_ERTH']?></cell>
		<cell><?php echo round($row['CNT_NICE_ERTH'] * 100 / $row['CNT_ERTH'],2)   ?></cell>
	</row>
	<row id="2">
		<cell><![CDATA[ <b>Здания:</b> ]]></cell>
		<cell><?php echo $row['CNT_HOME']?></cell>
		<cell><?php echo $row['CNT_BAD_HOME']?></cell>
		<cell><?php echo $row['CNT_NICE_HOME']?></cell>
		<cell><?php echo round($row['CNT_NICE_HOME'] * 100 / $row['CNT_HOME'],2) ?></cell>
	</row>
	<row id="3">
		<cell><![CDATA[ <b>Помещения:</b> ]]></cell>
		<cell><?php echo $row['CNT_APTM']?></cell>
		<cell><?php echo $row['CNT_BAD_APTM']?></cell>
		<cell><?php echo $row['CNT_NICE_APTM']?></cell>
		<cell><?php echo round($row['CNT_NICE_APTM'] * 100 / $row['CNT_APTM'],2) ?></cell>
	</row>
	<row id="4">
		<cell><![CDATA[ <b>Всего:</b> ]]></cell>
		<cell><![CDATA[ <b><?php echo $row['TOTAL_DOC']?></b> ]]></cell>
		<cell><![CDATA[ <b><?php echo $row['CNT_BAD']?></b> ]]></cell>
		<cell><![CDATA[ <b><?php echo $row['CNT_NICE']?></b> ]]></cell>
		<cell><![CDATA[ <b><font color="red"><?php echo round($row['CNT_NICE'] * 100 / $row['TOTAL_DOC'],2) ?></font></b> ]]></cell>
	</row>

</rows>

//@oci_free_statement($stid);
//@oci_close($connLOC);
*/
?>