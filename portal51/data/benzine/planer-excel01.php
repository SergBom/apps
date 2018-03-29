<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

//////////////////////////////////////////////////////////////////////////////
/*---------------------------------------------------------------------------*/
$fname = "AutoTrack"; // Имя выходного файла
/*---------------------------------------------------------------------------*/
$StartDataCol = 0;	// Стартовая колонка для данных
$StartDataRow = 4;	// Стартовая строка для данных
$str_num=$StartDataRow;	// С какой строки начинаются данные
$row_num=1;		// Счетчик строк

// Координаты колонок для различных типов данных
$c = array(
	'begin_date'=>$StartDataCol,
	'begin_time'=>$StartDataCol+1,
	'duration'=>$StartDataCol+2,
	'otdel'=>$StartDataCol+3,
	'type_tour'=>$StartDataCol+5,
	'tour_success'=>$StartDataCol+6,
	'tour_head'=>2,
	'tour_from'=>3,
	'tour_to'=>5,
	'distance'=>6
	);




/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

/****************************************************************
	Постоянные величины
	Исходные значения
*/
setlocale(LC_ALL, 'rus');
$one_day = 24*60*60;
$week_day = $one_day * 7;
$cur_time = time(); 
$data = array();
/****************************************************************/

/*-------------------------- Входные переменные -----------------------------*/
$Y = (int)substr($_GET['startOfWeek'],1,4);
$M = (int)substr($_GET['startOfWeek'],5,2);
$D = (int)substr($_GET['startOfWeek'],8,2);
/*---------------------------------------------------------------------------*/

/* Ищем начало и конец недели */
$week_start = strtotime("last Monday",mktime(0,0,0,$M,$D,$Y));
$week_end = $week_start+ 6 * $one_day  ; 
$ws = date('Y-m-d', $week_start+$week_day);
$we = date('Y-m-d', $week_end+$week_day);


/*********************************************************************/
// Создаем книгу на основе шаблона		
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
	
$objPHPExcel = $objReader->load("xlst/Out12.xlsx");
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

/*********************************************************************/
// Заголовок
$objWorksheet->setCellValue('A2', "c $ws по $we");
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


$style1 = array(
	'font' => array(
		'underline' => true
	)
);




	$sql = "SELECT * FROM `v#planer` WHERE begin_date>='$ws' AND begin_date<='$we' ORDER BY begin_date, begin_time ";
	//echo $sql;
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			/*** Преобразование полученных данных ***/
			if( (int)$row['delta'] > 0 AND (int)$row['tour_success'] == 0 ){$row['tour_success']='2';}
			switch ($row['tour_success']){
				case "0": $row['tour_success'] = 'Запланирована'; break;
				case "1": $row['tour_success'] = 'Завершена'; break;
				case "2": $row['tour_success'] = 'Просрочена'; break;
			}
			
			$objWorksheet->setCellValueByColumnAndRow( $c['begin_date'], $str_num, $row['begin_date'].'  '. $row['week_day']);
			$objWorksheet->setCellValueByColumnAndRow( $c['begin_time'], $str_num, $row['begin_time']);
			$objWorksheet->setCellValueByColumnAndRow( $c['duration'], $str_num, $row['duration']);
			$objWorksheet->setCellValueByColumnAndRow( $c['otdel'], $str_num, $row['otdel']);
			$objWorksheet->setCellValueByColumnAndRow( $c['type_tour'], $str_num, $row['type_tour']);
			$objWorksheet->setCellValueByColumnAndRow( $c['tour_success'], $str_num, $row['tour_success']);
			
			
			/*** Добавляем список маршрутов к записи***/
			$str_num ++;
			$objWorksheet->setCellValueByColumnAndRow( $c['tour_head'], $str_num, 'Маршрут:');
			echo $objWorksheet->getCellByColumnAndRow($c['tour_head'], $str_num)->getCoordinate();
			
			//->applyFromArray($style1);
			
			$r2 = $db->query( "SELECT * FROM `v#tours` WHERE tour_id='{$row['id']}'" );
			while ($row2 = $r2->fetch_assoc()) {
				$str_num ++;

				$objWorksheet->setCellValueByColumnAndRow( $c['tour_from'],   $str_num, $row2['tour_from']);
				$objWorksheet->setCellValueByColumnAndRow( $c['tour_from']+1, $str_num, '>');
				$objWorksheet->setCellValueByColumnAndRow( $c['tour_to'],     $str_num, $row2['tour_to']);
				$objWorksheet->setCellValueByColumnAndRow( $c['distance'],    $str_num, $row2['distance'].' км');
				
			}
			/***------------------------------------***/

			/*** Добавляем коментарий к записи***/
			$str_num ++;
			$objWorksheet->setCellValueByColumnAndRow( $c['tour_head'], $str_num, 'Пояснение:');
			
			$str_num ++;
			$objWorksheet->setCellValueByColumnAndRow( $c['tour_from'], $str_num, $row2['reference']);
			/***------------------------------------***/
			
			$str_num++; // Номер текущей строки
			$row_num++; // Порядковый номер записи
			
		}

		//echo json_encode(array('success'=>true,'data'=>$data));
	}




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
/*$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
	*/

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
	$objWorksheet->getStyle('A4:'.$hCell)->applyFromArray($styleArray); // Обрамляем ее
	
//*****************************************************************************
// Расставляем формулы сумма по колонкам
/*	for ($i=1; $i<=count($c)*3; $i++){
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
*/	
	//echo json_encode(array(	'success'=>'true', 'data'=>$data1 ));
//*****************************************************************************
// И полетело все это в файлик
	xlsxOutFile($objPHPExcel, $fname);	
?>
