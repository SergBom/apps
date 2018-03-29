<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
$uploaddir = "files/";
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('Deads');
/*---------------------------------------------------------------------------*/
 
$log = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';
 
// $f = array();
 
// Загружаем файл
		$id = rand(10000000,99999999);
        $file = $_FILES['filename'];
        $fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
		$fileType = $file['type'];
		$fileType2 = substr(strrchr($fileName, '.'), 1);
        if (!$fileSize) {
            $fileSize = $_SERVER['CONTENT_LENGTH'];
        }

		if( $fileSize <= 20000000 ){
			
			$fileDoc = "DOC_".str_pad($id, 10, "0", STR_PAD_LEFT).".$fileType2";
			$fname = $uploaddir.$fileDoc;
			if( file_exists($fname) ){ unlink($fname); }

			if( move_uploaded_file($fileTmpName, $fname ) )  {	
			//}
			
			//if( file_exists( $wfile ) ){

							$f_str = file($fname);
					
							$marker = trim($f_str[0]);
							if($marker == '==ЗАГС=='){
								
								$spam = false;
								
								$log .= "<b>$marker</b><br>\n";

								$s = explode('|',$f_str[1]);
								$place = rtrim($s[0]);

								for ( $fi=2; $fi<count($f_str); $fi++ ){
				
									$str = explode('|',$f_str[$fi]);
				
									if( trim($str[0]) <> '' ){
				
										$FIO = trim($str[0]);
										$DR = dpars($str[1]);
										$Period1 = dpars($str[3]);
										$Period2 = dpars($str[4]);
										$MR = trim($str[2]);
				
										$sql = "INSERT INTO main SET
										FIO='$FIO', DR='$DR', MR='$MR', Period1='$Period1', Period2='$Period2', Place='$place'
										ON DUPLICATE KEY UPDATE FIO='$FIO', DR='$DR', Place='$place'
										";

										//echo $sql."<br>";
					
										$db->query($sql);
					
									}
								}
								echo json_encode(array(
									"success" => true,
									"fileName" => $fileName,
									"msg" => "Файл загружен в хранилище"
								));
								
							} else {
								// Файл не из ЗАГСА
								$log .= "*** Файл не из ЗАГСА *** '$marker' ***<br>\n";
								echo json_encode(array(
									"success" => false,
									"msg" => "Не верный формат файла"
								));								
							}
							// delete file
							unlink($fname);

			
				
			}
		}
		
/*

			echo json_encode(array(
				"success" => true,
				"fileName" => $fileName,
				"message" => "Файл загружен в хранилище"
			));
*/
function dpars($d){
	$R = date_parse($d);
	return $R['year'] .".". $R['month'] .".". $R['day'];
}


/*				$f = file($wfile);
				$s = explode('|',$f[0]);
				$PRIZNAK = rtrim($s[0]);

				if($PRIZNAK == "==ЗАГС=="){
				
					$s = explode('|',$f[1]);
					$place = rtrim($s[0]);
				
				
				
					for ( $i=1; $i<count($f); $i++ ){
				
						$str = explode('|',$f[$i]);
				
						if( trim($str[0]) <> '' ){ 
				
							$FIO = trim($str[0]);
							$DR = dpars($str[1]);
							$Period1 = dpars($str[3]);
							$Period2 = dpars($str[4]);
							$MR = trim($str[2]);
				
							$sql = "INSERT INTO main SET
							FIO='$FIO', DR='$DR', MR='$MR', Period1='$Period1', Period2='$Period2', Place='$place'
							ON DUPLICATE KEY UPDATE FIO='$FIO', DR='$DR', Period1='$Period1'
							";

//						echo $sql."<br>";
					
							$db->query($sql);
					
						}
					}
					echo json_encode(array(
						"success" => true,
						"fileName" => $fileName,
						"msg" => "Файл загружен в хранилище"
					));
				} else {
					echo json_encode(array(
						"success" => false,
						"msg" => "Не верный формат файла"
					));

				}
				unlink($wfile);
*/


?>