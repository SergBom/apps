<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

//////////////////////////////////////////////////////////////////////////////
// Принимаем параметры
//$DateB = "09.01.".date('Y');
//$DateBegin = trim ((!empty($_GET['db'])) ? $_GET['db'] : $DateB ); // По умолчанию - Начало года
//$DateEnd = trim ((!empty($_GET['de'])) ? $_GET['de'] : date('d.m.Y') ); // По умолчанию - Сегодня
//$Otdel = trim ((!empty($_GET['otd'])) ? $_GET['otd'] : 0 ); // 

	//$data = array();
	$a = (object)$_REQUEST; //json_decode($info);

	$filter = "";
	if( isset($a->filter) ){
		$filter = get_filter($a->filter);
	}	

/*---------------------------------------------------------------------------*/
$fname = "GKU_report";		// Имя выходного файла
$ftemplate = "xlst/GKU-out.xltx"; // Шаблон
/*---------------------------------------------------------------------------*/
$StartDataRow = 5;			// Стартовая строка для данных
$str_start=$StartDataRow;	// С какой строки начинаются данные
$row_start=1;				// Счетчик строк
$PageSize = 18;  			// Количество строк БД на страницу


//***************************//
// Выводимые поля таблицы
$f0 = array(
'zayav'			,	
'zayav_date'	,
'ONvid_N'		,
'zayav_type_N'	,
'charc'			,
'KU_treb'		,
'CadEngineer_N'	,
'RR_date_uchet'	,
'RR_date_stop'	,
'RR_date_none'	,
'RR_FZ'			,
'RR_refer'		
);

$f = fieldEXL($f0);
//***************************//

//		
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
	
$objPHPExcel = $objReader->load($ftemplate);
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

// Set document properties
$objPHPExcel->getProperties()->setCreator("SBOM")
							 ->setLastModifiedBy("SBOM")
							 ->setTitle("ГКУ - Приостановления");
							 //->setSubject("Статистика использования ПК ПВД")
							 //->setDescription("Статистика использования ПК ПВД");

$styleArray = array(
	'font' => array(
		'size' => 12
	)
	,'alignment' => array(
		'wraptext' => true
//		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$styleArrayOtdel = array(
	'font' => array(
		'bold' => true,
		'size' => 12
	)
);

// Повторяем строку вначале каждой страницы печати
$objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(17, 17);	

//*********************************************************************
// Делаем выборку по каждому из 
//
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('GKUstop');

/*---------------------------------------------------------------------------*/

	$sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM `vData` WHERE 0=0 $filter ";
	
		$row_num = $row_start;//Сброс счетчика строк на начало
		$str_num = $str_start; //Сброс счетчика колонок на начало
	
	

	
			if ( $result = $db->query( $sql ) ) {

				while ($row = $result->fetch()) { //Выбираем строку

					//$objWorksheet->setCellValue("{$f['Npp']}$str_num", $Npp);
//					foreach( $f as $k => $v ){
						//$objWorksheet->setCellValue("{$v}$str_num", $row[$k]);
//					}
					
					$objWorksheet->setCellValue("{$f['zayav']}$str_num", 			$row['zayav']);					
					$objWorksheet->setCellValue("{$f['zayav_date']}$str_num", 		ToDate($row['zayav_date']));
					$objWorksheet->setCellValue("{$f['ONvid_N']}$str_num", 			$row['ONvid_N']);
					$objWorksheet->setCellValue("{$f['zayav_type_N']}$str_num", 	$row['zayav_type_N']);
					$objWorksheet->setCellValue("{$f['charc']}$str_num", 			$row['charc']);
					$objWorksheet->setCellValue("{$f['KU_treb']}$str_num", 			$row['KU_treb']);
					$objWorksheet->setCellValue("{$f['CadEngineer_N']}$str_num", 	$row['CadEngineer_N']);
					$objWorksheet->setCellValue("{$f['RR_date_uchet']}$str_num", 	ToDate($row['RR_date_uchet']));
					$objWorksheet->setCellValue("{$f['RR_date_stop']}$str_num", 	ToDate($row['RR_date_stop']));
					$objWorksheet->setCellValue("{$f['RR_date_none']}$str_num", 	ToDate($row['RR_date_none']));
					$objWorksheet->setCellValue("{$f['RR_FZ']}$str_num", 			$row['RR_FZ']);
					$objWorksheet->setCellValue("{$f['RR_refer']}$str_num", 		$row['RR_refer']);
					
					
					
				
					// Форматируем строку
//					$objWorksheet->getRowDimension("$str_num")->setRowHeight(45);
//					$objWorksheet->mergeCells("C$str_num:D$str_num");
			
					//$Npp++;		// Счетчик Нумерации позиций
					$str_num++;	// Переходим на новую строку
				}
			}


		$objWorksheet->getStyle("A$str_start:L$str_num")->getAlignment()->setWrapText(true);
		$objWorksheet->getStyle("A$str_start:L$str_num")->applyFromArray($styleArray);
		$objWorksheet->getPageSetup()->setPrintArea("A1:L$str_num");

	
//*****************************************************************************
// И полетело все это в файлик
	xlsxOutFile($objPHPExcel, $fname);	
//*****************************************************************************










///***********************************************************///	
///***********************************************************///	
///***********************************************************///	
function get_filter($_filter){
	
	if( count($_filter)> 0 ){
		$filters = json_decode($_filter,true);
		
		$ff_v = array();

		foreach($filters as $f){
			
			if( @$f['isDateValue'] ){ // Если дата
				$fk = substr($f['_value'], 0, 10);
			} else {
				if( preg_match('/,/',$f['_value'] )){
					$fk = implode('","',$f['_value']); // Данные в виде массива
				}else{
					$fk = $f['_value'];
				}
			}
			
			switch ( $f['_operator'] ){
				case 'in':
					$ff_v[] = "{$f['_property']} in (\"$fk\")";
					break;
				case 'gt':
					$ff_v[] = "{$f['_property']} > \"$fk\"";
					break;
				case 'lt':
					$ff_v[] = "{$f['_property']} < \"$fk\"";
					break;
				case 'like':
					$ff_v[] = "{$f['_property']} like \"%$fk%\"";
					break;
			}
		}

		//print_r(count($ff_v));
		//echo  "<hr>";
		if( count($ff_v)>0 ){
			return " AND ". implode(' AND ', $ff_v);
		} else {
			return "";
		}
	}
}	
///*******************///
function fieldEXL($ff){
	$r = range('A', 'Z');
	$o = array();
	for ($i=0; $i<count($ff); $i++ ){
		$o[ $ff[$i] ] = $r[$i];
	}
	return $o;
}
///*******************///
function ToDate($s){
	// 2018-11-22
	return   substr($s,-2).".". substr($s,5,2).".".substr($s,0,4);
}
///*******************///

	
?>
