<?php
/**************************************************************
 Статистика общая
*/
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");
/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
/****************************************************************************/
$fname = "stat01"; // Имя выходного файла

/*---------------------------------------------------------------------------*/
$StartDataCol = 2;	// Стартовая колонка для данных
$StartDataRow = 10;	// Стартовая строка для данных
$str_num=$StartDataRow;	// С какой строки начинаются данные
$row_num=1;		// Счетчик строк

// Координаты колонок для различных типов данных
$c = array(
	'EGRP_GRP'=>$StartDataCol,
	'EGRP_PS'=>$StartDataCol+1,
	'OKS_GU'=>$StartDataCol+2,
	'OKS_PS'=>$StartDataCol+3,
	'OKU_GKU'=>$StartDataCol+4,
	'OKU_PS'=>$StartDataCol+5,
	'SUM'=>$StartDataCol+6
	);

	$linkPVD = ConnectOciDB('SB_PVD'); // Открываем линк к базе ПВД (куда все сливается)

	$stLink = oci_sql_exec($linkPVD, "SELECT * FROM T\$PARAMS");
	while ($rowLink = oci_fetch_assoc($stLink)) {
		if($rowLink["NAME"]=="Start_date"){ $DateB = $rowLink["PARAM"]; }
		if($rowLink["NAME"]=="Current_table"){ $Current_table = $rowLink["PARAM"]; }
	}
		
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
	
$objPHPExcel = $objReader->load("xlst/OutPvd1.xltx");
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);


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