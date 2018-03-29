<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

//$_GET = array_change_key_case($_GET);
//////////////////////////////////////////////////////////////////////////////
// Принимаем параметры
$DateB = "09.01.".date('Y');
$DateBegin = trim ((!empty($_GET['db'])) ? $_GET['db'] : $DateB ); // По умолчанию - Начало года
$DateEnd = trim ((!empty($_GET['de'])) ? $_GET['de'] : date('d.m.Y') ); // По умолчанию - Сегодня
//$Otdel = trim ((!empty($_GET['otd'])) ? $_GET['otd'] : 0 ); // 
/*---------------------------------------------------------------------------*/
$fname = "PVDstat_01"; // Имя выходного файла
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

//$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
//$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
//$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
	
/*******************************************************************************/	
// Create new PHPExcel object
//$objPHPExcel = new PHPExcel();

// Set document properties
/*$objPHPExcel->getProperties()->setCreator("SBOM")
							 ->setLastModifiedBy("SBOM")
							 ->setTitle("Статистика использования ПК ПВД")
							 ->setSubject("Статистика использования ПК ПВД")
							 ->setDescription("Статистика использования ПК ПВД");
*/
/*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Статистика использования ПК ПВД по серверам');
			*/
$objWorksheet->setCellValue('A2', "c $DateBegin по $DateEnd");

$styleArray = array(
	'font' => array(
		'bold' => true,
		'size' => 12
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	),
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		),
	)
);
/*$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
	*/

//******************************************************
// Разделяем пользователей системы ПК ПВД
//**********************************//
//				AND upper(U_LOGIN) not like upper('reestr%') 
//OR ( U_JOB is NULL and upper(U_LOGIN) not like upper('reestr%') )
$likeOUR =	" AND ( not REGEXP_LIKE(upper(U_JOB),upper('Техник|Инженер|Заместитель') )
				)
				AND upper(U_LOGIN) not like upper('reestr%')
				AND U_LOGIN not like 'admin'
				";
$likeKP =	" AND upper(U_JOB) not like upper('специалист')
				AND upper(U_LOGIN) not like upper('murmansk\%')
				AND upper(U_LOGIN) not like upper('reestr%')
				AND U_LOGIN not like 'admin'
					";
$likeMFC = 	" AND upper(U_LOGIN) like upper('reestr%')
				AND U_LOGIN not like 'admin'
";

//*********************************************************************
// Делаем выборку по каждому из серверов ПВД
//
	$stLink = oci_sql_exec($linkPVD, "SELECT * FROM T\$TLINK ORDER BY NAME_LINK");
	while ($rowLink = oci_fetch_assoc($stLink)) {

		//****************************************************************
		// Выводим наименования серверов ПВД
		$objWorksheet->setCellValueByColumnAndRow(0,"$str_num", "$row_num")			// Номер строки
					->setCellValueByColumnAndRow(1,"$str_num", $rowLink["NAME_TO"]) // Имя сервера ПВД
		;
	
		
		//******************************************************
		// Наш главный запросик
		$sql = "with OP_DOP as (";
		$sql .= "select 1 as \"N\", 'ROSREESTR' as \"ORG\", count(cs.id) as \"COUNT_DEL\", ID_PROC as \"ID_PROC\", 'FULL' as \"TYPE\"
				from $Current_table cs
				where 
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
                    AND CS.ID_LINK='{$rowLink['NAME_LINK']}'
				group by ID_PROC
				";
				
		$sql .= " union
				select 1 as \"N\", 'KP' as \"ORG\", count(cs.id) as \"COUNT_DEL\", ID_PROC as \"ID_PROC\", 'FULL' as \"TYPE\"
				from $Current_table cs
				where 
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeKP}
                    AND CS.ID_LINK='{$rowLink['NAME_LINK']}'
				group by ID_PROC
				";
				
		$sql .= " union
				select 1 as \"N\", 'MFC' as \"ORG\", count(cs.id) as \"COUNT_DEL\", ID_PROC as \"ID_PROC\", 'FULL' as \"TYPE\"
				from $Current_table cs
				where 
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeMFC}
                    AND CS.ID_LINK='{$rowLink['NAME_LINK']}'
				group by ID_PROC
				";

		$sql .= ") select N, ORG, COUNT_DEL, PP.ID_PROC, TYPE, P.TEXT
					from OP_DOP PP
					left join  T\$PROC p on P.ID_PROC=pp.ID_PROC
					order by N asc, P.IDORDER asc";
		
//
//	CASE {$case}
//	{$where}
//echo "<p>$sql</p><br>";
		
		$st2 = oci_sql_exec($linkPVD, $sql);
		while ($row = oci_fetch_assoc($st2)) {
		
			switch ($row['ORG']){
				case 'ROSREESTR' : $d_cl = 0; break;
				case 'KP' : $d_cl = 1; break;
				case 'MFC' : $d_cl = 2; break;
			}

			$objWorksheet->setCellValueByColumnAndRow( $c[$row['ID_PROC']]+count($c)*$d_cl, $str_num, $row['COUNT_DEL']);
					//echo $row['ORG'].' ->'.$row['ID_PROC'].'->'.($c[$row['ID_PROC']]+count($c)*$d_cl)."<br>";
			
		}
					

		for( $i=1; $i<=3; $i++){
			$col  = stringFromColumnIndex( $StartDataCol + count($c) * $i - 1 );
			$col1 = stringFromColumnIndex( $StartDataCol + count($c) * $i - count($c) );
			$col2 = stringFromColumnIndex( $StartDataCol + count($c) * $i - 1 - 1 );
			$objWorksheet->setCellValue( $col.($str_num),"=SUM(".$col1.$str_num.":".$col2.$str_num.")" );
		}

		$str_num++; // Номер текущей строки
		$row_num++; // Порядковый номер записи
	}
	//$row_num--;
//	echo ">> ".round($FULL_ALL1/$FULL_ALL*100,2)." %<br>";
//	echo ">> ".round($SMALL_ALL1/$SMALL_ALL*100,2)." %<br>";


//	$P1 = round( $FULL_ALL1/($FULL_ALL1+$EGRP)*100,2);
//	$P2 = round( $SMALL_ALL1/($SMALL_ALL1+$EGRP_SMALL)*100,2);

	//$data = array_merge( $dataE1, $data1, $dataP1 );
	
	//print_r($data);
	
/*
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	*/
	
	//**************************************************************************
	// Определяем нижние границы таблицы
	$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
	$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
	$hCell = $highestColumn.$highestRow;
	
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
//				'color' => array('argb' => 'FFFF0000'),
			),
		),
	);
	$objWorksheet->getStyle('A10:'.$hCell)->applyFromArray($styleArray); // Обрамляем ее
	
//*****************************************************************************
// Расставляем формулы сумма по колонкам
	for ($i=1; $i<=count($c)*3; $i++){
		$col = stringFromColumnIndex($StartDataCol+$i-1);
		$objWorksheet->setCellValue( $col.$str_num,"=SUM(".$col.($str_num-1).":".$col.$StartDataRow.")" );
	}
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
//				'color' => array('argb' => 'FFFF0000'),
			),
		),
		'font' => array(
			'bold' => 'true'
		)
	);
	$objWorksheet->getStyle('A'.$str_num.':'.$col.$str_num)->applyFromArray($styleArray); // Выделяем рамочкой строку с итогами
	$objWorksheet->setCellValue( 'B'.$str_num,"Всего:" );

	for( $i=1; $i<=3; $i++){
		$col  = stringFromColumnIndex( $StartDataCol + count($c) * $i - 1 );
		$objWorksheet->getStyle('A'.$str_num.':'.$col.$str_num)->applyFromArray($styleArray); // Выделяем рамочкой строку с итогами
		$col  = stringFromColumnIndex( $StartDataCol + count($c) * $i - 1 );
			$col1 = stringFromColumnIndex( $StartDataCol + count($c) * $i - count($c) );
			$col2 = stringFromColumnIndex( $StartDataCol + count($c) * $i - 1 - 1 );
		$objWorksheet->setCellValue( $col.($str_num),"=SUM(".$col1.$str_num.":".$col2.$str_num.")" );
	}
	
	//echo json_encode(array(	'success'=>'true', 'data'=>$data1 ));
//*****************************************************************************
// И полетело все это в файлик
	xlsxOutFile($objPHPExcel, $fname);	
?>
