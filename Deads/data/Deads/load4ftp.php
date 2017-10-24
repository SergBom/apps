<?php   
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/root/script" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/lib/init2.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
$workdir = "/media/hdb1/sftp/zags";
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('Deads');
/*---------------------------------------------------------------------------*/



 
 //$f = array();
 
// Загружаем файл
read_folder($workdir);

function read_folder($folder)
{
	global $db;
	$spam = true;
	
    $files = scandir($folder);
    foreach($files as $file)
    {
        if ($file == '.' || $file == '..') continue;
        $fname = $folder . '/' . $file;
        if (is_dir($fname)) {
			read_folder($fname);
		} else {
			echo $fname ."\n";
		
			$log = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';	
			
//				$fname = $workdir . '/murm/zags2rosreestr_2017_01_07_10_07.txt';

			///*******************************************///
			/// Обработка файла
			//$fname = 'files/DOC_0000275231.txt';
				
			$f_str = file($fname);
					
			$marker = trim($f_str[0]); // Удаляем BOM
			if( preg_match('/==ЗАГС==/i', $marker) ){ // == '==ЗАГС=='){
								
				$spam = false;
							
				$log .= "<b><font color='blue'>$fname</font></b><br>\n";
				$log .= "<b><font color='green'>$marker</font></b><br>\n";

				$s = explode('|',$f_str[1]);
				$place = rtrim($s[0]);

				for ( $fi=2; $fi<count($f_str); $fi++ ){
				
					$str = explode('|',$f_str[$fi]);
				
					if( trim($str[0]) <> '' ){
				
						$FIO = trim($str[0]);
						$DR = dpars($str[1]);
						$MR = trim($str[2]);
						$Period1 = dpars($str[3]);
//										$Period2 = dpars($str[4]);
				
						$sql = "INSERT INTO main SET
						FIO='$FIO', DR='$DR', MR='$MR', Period1='$Period1', Period2='$Period1', Place='$place'
						ON DUPLICATE KEY UPDATE FIO='$FIO', DR='$DR', Place='$place'
						";

						$log .=  $sql."<br>\n";
					
						$db->query($sql);
			
					}
				}
				$log .=  "<hr>\n";
				
			} else {
				// Файл не из ЗАГСА
				$log .= "<font color='red'>*** Файл не из ЗАГСА *** '$marker' ***</font><hr>\n";
				$spam = true;
			}
			// delete file
			unlink($fname);
			

			if( $spam ){
				//$messageset = implode(",",$i);
				//_move($connection,"$i",$spam_box);
			} else {
				//_delete($connection,$i);
			}
							

							
			$log .= '</body></html>';

			mail_send( array(
				'to_email' => 'admin@r51.rosreestr.ru',
				'from_email' => 'zags2d@r51.rosreestr.ru',
				'subject' => 'Отчет по принятым письмам',
				'message' => $log
			));
				
		}

    }
}

				
				
function dpars($d){
	$R = preg_split('/\//',$d);
	return $R[2] .".". $R[1] .".". $R[0];
}			
/*function dpars($d){
	$R = date_parse($d);
	return $R['year'] .".". $R['month'] .".". $R['day'];
}*/

?>