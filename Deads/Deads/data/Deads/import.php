<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

$dir = 'ЗАГС';






loadDir($dir);
//////////////////////////////////////
function loadDir($dirr)
{
   static $deep = 0;
 
   $odir = opendir($dirr);
   echo "'".$dirr."'<br>";
 
   while (($file = readdir($odir)) !== FALSE)
   {
      if ($file == '.' || $file == '..')
      {
         continue;
      }
      else
      {
		  if( substr($file,-4) == '.xls' ){
			
			$a =  explode('/',$dirr);
			$b = $a[ count($a)-1 ];
			
			echo str_repeat('---', $deep).$dirr . DIRECTORY_SEPARATOR . $file."       {$b}".'<br>';
			
			getExcel($dirr . DIRECTORY_SEPARATOR . $file,'Excel2003XML');
			
		  } else
		  if( substr($file,-5) == '.xlsx' ){
			
			$a =  explode('/',$dirr);
			$b = $a[ count($a)-1 ];
			
			echo str_repeat('---', $deep).$dirr . DIRECTORY_SEPARATOR . $file."       {$b}".'<br>';
			
			getExcel($dirr . DIRECTORY_SEPARATOR . $file,'Excel2007');
			
		  }

	  }
 
      if (is_dir($dirr . DIRECTORY_SEPARATOR . $file))
      {
         $deep ++;
         loadDir($dirr. DIRECTORY_SEPARATOR .$file);
         $deep --;
      }
   }
      closedir($odir);
}

function getExcel($inputFileName,$fType){
	
//echo 'test<br>'	;
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);	
var_dump($objPHPExcel);
	/*
			$objReader = PHPExcel_IOFactory::createReader($fType);
			$objReader->setReadDataOnly(false);
	
			$objPHPExcel = $objReader->load($inputFileName);
			//$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($inputFileName.'x');
	
//	try {
	//    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
//	} catch (PHPExcel_Reader_Exception $e) {
//	    die('Error loading file: '.$e->getMessage());
//	}			
			
	*/
}



?>