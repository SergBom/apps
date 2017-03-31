<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

// Принимаем параметры
$year = $_GET['year'];
$type = @$_GET['type'];
/*---------------------------------------------------------------------------*/
$fname = "statDPD"; // Имя выходного файла
$ftemp = "xlst/statDPD.xlsx"; // Имя файла шаблона
/*---------------------------------------------------------------------------*/
$StartDataCol = 2;	// Стартовая колонка для данных
$StartDataRow = 8;	// Стартовая строка для данных
$str_num=$StartDataRow;	// С какой строки начинаются данные
$row_num=1;		// Счетчик строк
/*---------------------------------------------------------------------------*/

$Summ = array(
'name'=>'ИТОГО',
'm01'=>0,'m02'=>0,'m03'=>0,'m04'=>0,'m05'=>0,'m06'=>0,'m07'=>0,'m08'=>0,'m09'=>0,'m10'=>0,'m11'=>0,'m12'=>0,
'summa'=>0
);

$SummMax = array(
'name'=>'По нарастающему',
'm01'=>0,'m02'=>0,'m03'=>0,'m04'=>0,'m05'=>0,'m06'=>0,'m07'=>0,'m08'=>0,'m09'=>0,'m10'=>0,'m11'=>0,'m12'=>0,
'summa'=>0
);
/*---------------------------------------------------------------------------*/

// Координаты колонок для различных типов данных
$c = array(
	'name'=>$StartDataCol,
	'm01'=>$StartDataCol+1,
	'm02'=>$StartDataCol+2,
	'm03'=>$StartDataCol+3,
	'm04'=>$StartDataCol+4,
	'm05'=>$StartDataCol+5,
	'm06'=>$StartDataCol+6
	'm07'=>$StartDataCol+7
	'm08'=>$StartDataCol+8
	'm09'=>$StartDataCol+9
	'm10'=>$StartDataCol+10
	'm11'=>$StartDataCol+11
	'm12'=>$StartDataCol+12
	'summa'=>$StartDataCol+13
	);

	$db = ConnectPDO('Scan_Docs'); // Открываем линк к базе

/*	$stLink = oci_sql_exec($linkPVD, "SELECT * FROM T\$PARAMS");
	while ($rowLink = oci_fetch_assoc($stLink)) {
		if($rowLink["NAME"]=="Start_date"){ $DateB = $rowLink["PARAM"]; }
		if($rowLink["NAME"]=="Current_table"){ $Current_table = $rowLink["PARAM"]; }
	}
	*/
	
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
	
$objPHPExcel = $objReader->load(;аеуьз);
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
$objWorksheet->setCellValue('G3', "на $year год");

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

//*********************************************************************

    $db = ConnectPDO('Scan_docs');

	// Сумма всех дел за весь предыдущий период
	$SummOld = $db->query( "select count(*) from v_docs3 where cdate < str_to_date( '$year-01-01','%Y-%m-%d')" )->fetchColumn();
	//echo $SummOld.$cr;
	
	// Получаем список отделов
	if( $type == 1 ){
		$stm_otdels = $db->query( "select * from otdels ORDER BY id" ); //->fetchAll(PDO::FETCH_COLUMN);
	} else if( $type == 2 ){
		$stm_otdels = $db->query( "SELECT DISTINCT n1 as id, n2 as name FROM t\$district_data ORDER BY n1" ); 
	}

	$data = array();
	
	
	while( $row_otdels = $stm_otdels->fetch() ){ // Строку отдела

		if( $type == 1 ){
			$sql = "CALL `statCommon`('{$year}',{$row_otdels['id']})");
		} else if( $type == 2 ){
			$sql = "CALL `statCommon2`('{$year}',{$row_otdels['id']})");
		}
		
			$d = $db->query( $sql );
		
					//->fetchAll(); //PDO::FETCH_COLUMN);
		while( $row = $d->fetch() ){



		
			// Суммируем по месяцам
			for($i=0; $i<=13; $i++ ){
				
				$objWorksheet->setCellValueByColumnAndRow( $i+1, $str_num, $row[$i]);
				
				if($i>0 and $i<13){
					$o = str_pad($i, 2, "0", STR_PAD_LEFT);
					$Summ["m$o"] = $Summ["m$o"] + $d[0]["m$o"];
				}	
			}

			$Summ["summa"] = $Summ["summa"] + $d[0]["summa"];
		
			$d[0] = array('name'=>$row_otdels['name']) + $d[0] ;
			$d[0]["summa"] = 	"<b>" .	$d[0]["summa"] . "</b>";

			array_push($data,$d[0]);
		}
		$str_num++;
	}

	$S = 0;
	foreach( $Summ as $k=>$v ){
		$S += $v;
		$S2 = $SummMax[$k] + $S + $SummOld;
		
		$Summ[$k]		= "<font color='red'><b>" . $v . "</b></font>";
		$SummMax[$k]	= "<font color='green'><b>" . $S2 . "</b></font>";
		
	}
	
	$SummMax["name"] = "<font color='green'><b>По нарастающему</b></font>";

	array_push($data,$Summ);
	array_push($data,$SummMax);
	
	echo json_encode(array('success'=>'true','data'=>$data));





	$stLink = oci_sql_exec($linkPVD, "SELECT * FROM T\$TLINK ORDER BY NAME_LINK");
	while ($rowLink = oci_fetch_assoc($stLink)) {

		//****************************************************************
		// Выводим наименования серверов ПВД
		$objWorksheet->setCellValueByColumnAndRow(0,"$str_num", "$row_num")			// Номер строки
					->setCellValueByColumnAndRow(1,"$str_num", $rowLink["NAME_TO"]) // Имя сервера ПВД
		;
	
		
		//******************************************************
		// Наш главный запросик
		
		
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
