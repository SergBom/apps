<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/TimeHelper.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");


//$_GET = array_change_key_case($_GET);
//////////////////////////////////////////////////////////////////////////////
// Принимаем параметры
$Year = $_GET['Year'];
$Npp  = $_GET['Npp'];

/*---------------------------------------------------------------------------*/
$fname = "OpisDel_"; // Имя выходного файла
/*---------------------------------------------------------------------------*/
$StartDataRow = 18;	// Стартовая строка для данных
$str_start=$StartDataRow;	// С какой строки начинаются данные
$row_start=1;		// Счетчик строк
$PageSize = 18;  // Количество строк БД на страницу


// Выводимые поля таблицы
$f = array(
'Npp'=>'A'
,'Index'=>'B'
,'Title'=>'C'
,'Dates'=>'E'
,'Listov'=>'F'
,'Refer'=>'G'
);

//		
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
	
$objPHPExcel = $objReader->load("xlst/OutOpis12.xltx");
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

//$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
//$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
//$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
	
/*******************************************************************************/	
// Create new PHPExcel object
//$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SBOM")
							 ->setLastModifiedBy("SBOM")
							 ->setTitle("Опись дел за {$Year} год");
							 //->setSubject("Статистика использования ПК ПВД")
							 //->setDescription("Статистика использования ПК ПВД");

//setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'Russian_Russia.UTF-8');
//$date = TimeHelper::create()->today(); // date("d F Y");
$objWorksheet->setCellValue('A14', " за {$Year} г.");

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

    $db = ConnectMyDB('OpisDel');

	
		$row_num = $row_start;//Сброс счетчика строк на начало
		$str_num = $str_start; //Сброс счетчика колонок на начало
	
	
	$sql = "select distinct InsertOtdel from `Opis` WHERE Year={$Year} ORDER BY TitleNumber";
	//Список отделов в порядке, начиная с Отдела Регистрации
	if ( $resOtdel = $db->query( $sql ) ) {
		while ($rowOtdel = $resOtdel->fetch_assoc()) { //Выбираем строку
		
			// Печатаем заголовок Отдела
			$objWorksheet->mergeCells("A$str_num:G$str_num");
			$objWorksheet->setCellValue("A$str_num", str_replace("Мур.область ","",$rowOtdel['InsertOtdel']));
			$objWorksheet->getStyle("A$str_num:A$str_num")->applyFromArray($styleArrayOtdel);
			$str_num++; // Переходим на новую строку
	
			// Выборка данных по данному отделу
			$sql = "select SQL_CALC_FOUND_ROWS 
				concat(Npp,'.') as Npp,`Index`,
				concat(TitleBook,', ',TitleNumber) as Title,
				concat(date_format(DateBegin,'%d.%m.%Y'),' ',date_format(DateEnd,'%d.%m.%Y')) as Dates,
				Listov, Refer
				from `Opis` WHERE Year={$Year} AND InsertOtdel='{$rowOtdel['InsertOtdel']}'
				ORDER BY TitleBook desc, TitleNumber, InsertOID, Npp";
			if ( $result = $db->query( $sql ) ) {

				while ($row = $result->fetch_assoc()) { //Выбираем строку

					$objWorksheet->setCellValue("{$f['Npp']}$str_num", $Npp);
					$objWorksheet->setCellValue("{$f['Index']}$str_num", $row['Index']);
					$objWorksheet->setCellValue("{$f['Title']}$str_num", $row['Title']);
					$objWorksheet->setCellValue("{$f['Dates']}$str_num", $row['Dates']);
					$objWorksheet->setCellValue("{$f['Listov']}$str_num", $row['Listov']);
					$objWorksheet->setCellValue("{$f['Refer']}$str_num", $row['Refer']);
				
					// Форматируем строку
					$objWorksheet->getRowDimension("$str_num")->setRowHeight(45);
					$objWorksheet->mergeCells("C$str_num:D$str_num");
			
					$Npp++;		// Счетчик Нумерации позиций
					$str_num++;	// Переходим на новую строку
				}
			}
		}
	}
	
	
	
	/*
	$sql = "select SQL_CALC_FOUND_ROWS 
				concat(Npp,'.') as Npp,`Index`,
				concat(TitleBook,', ',TitleNumber) as Title,
				concat(date_format(DateBegin,'%d.%m.%Y'),' ',date_format(DateEnd,'%d.%m.%Y')) as Dates,
				Listov, Refer, replace(InsertOtdel, 'Мур.область ', '') as InsertOtdel
				from `Opis` WHERE Year={$Year} ORDER BY TitleBook desc, TitleNumber, InsertOID, Npp";
	//echo "$sql<br>";

	if ( $result = $db->query( $sql ) ) {
		
		//$total_rows=$db->getOne("select FOUND_ROWS()");
		
		//$row_num = $row_start;//Сброс счетчика строк на начало
		//$str_num = $str_start; //Сброс счетчика колонок на начало
		$otdel_S = "";
		while ($row = $result->fetch_assoc()) { //Выбираем строку
			
			
			if($row['InsertOtdel'] != $otdel_S ){ // Если отдел новый, то вставляем заголовок

				$objWorksheet->mergeCells("A$str_num:G$str_num");
				$objWorksheet->setCellValue("A$str_num", $row['InsertOtdel']);
				$objWorksheet->getStyle("A$str_num:A$str_num")->applyFromArray($styleArrayOtdel);
				
				$otdel_S = $row['InsertOtdel'];
				$str_num++;
			} 

				$objWorksheet->setCellValue("{$f['Npp']}$str_num", $Npp);
				$objWorksheet->setCellValue("{$f['Index']}$str_num", $row['Index']);
				$objWorksheet->setCellValue("{$f['Title']}$str_num", $row['Title']);
				$objWorksheet->setCellValue("{$f['Dates']}$str_num", $row['Dates']);
				$objWorksheet->setCellValue("{$f['Listov']}$str_num", $row['Listov']);
				$objWorksheet->setCellValue("{$f['Refer']}$str_num", $row['Refer']);
				
				// Форматируем строку
				$objWorksheet->getRowDimension("$str_num")->setRowHeight(45);
				$objWorksheet->mergeCells("C$str_num:D$str_num");
			
			$Npp++;
			$str_num++;
			//$objWorksheet->insertNewRowBefore($str_num, 1);
		}

	}
	*/


		$objWorksheet->getStyle("A$str_start:G$str_num")->getAlignment()->setWrapText(true);
		$objWorksheet->getStyle("A$str_start:G$str_num")->applyFromArray($styleArray);
		$objWorksheet->getPageSetup()->setPrintArea("A1:G$str_num");

	
//*****************************************************************************
// И полетело все это в файлик
	xlsxOutFile($objPHPExcel, $fname);	
?>
