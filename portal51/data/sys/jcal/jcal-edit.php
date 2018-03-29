<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//$uploaddir = $_SERVER["DOCUMENT_ROOT"].'apps/refusals/data/Refusals/files/';
$uploaddir = "files/";

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];

	//print_r($adata);
	
	//$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	//$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;


//	$adata = (object)$_POST;


        $file = $_FILES['xml'];
        $fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
		$fileType = $file['type'];
		$fileType2 = substr(strrchr($fileName, '.'), 1);
        if (!$fileSize) {
            $fileSize = $_SERVER['CONTENT_LENGTH'];
        }

		if( $fileSize <= 20000000 ){
			
			$fileDoc = "xml_jcal_".str_pad($id, 5, "0", STR_PAD_LEFT).".$fileType2";
			if( move_uploaded_file($fileTmpName, $uploaddir.$fileDoc ) )  {			
	
				// Парсим схему XML и кидаем ее в базу
				$xml = simplexml_load_file( $uploaddir.$fileDoc );

				// Здесь корень схемы
				$attr = iterator_to_array($xml->attributes());
				
				// Парсим блок Праздников
				foreach($xml->holidays[0] as $a=>$b) {
				
					$c = $db->getOne("SELECT count(*) cnt FROM `prt#jcal_holiday` WHERE id_h=".$b['id']." AND y='".$attr['year'][0]."'");
					if($c == '0'){
						$db->query("INSERT INTO `prt#jcal_holiday` SET
							id_h='".$b['id']."',
							y='".$attr['year'][0]."',
							title='".$b['title']."'"
						);						
					}
				}

				// Парсим блок дней
				foreach($xml->days[0] as $a=>$b) {
				
					$dd = $attr['year'][0].".".$b['d'];
				
					$c = $db->getOne("SELECT count(*) cnt FROM `prt#jcal` WHERE c_date='".$dd."'");
					if($c == '0'){
						$db->query("INSERT INTO `prt#jcal` SET
							c_year='".$attr['year'][0]."',
							c_date='".$dd."',
							c_typeday='".$b['t']."',
							c_holiday='".$b['h']."'"
						);						
					}
				}
	
			}
			
			echo json_encode(array(
				"success" => true,
				"fileName" => $fileName,
				"message" => "Файл загружен в хранилище"
			));
			
		} else {
			echo json_encode(array(
				"success" => false,
				"message" => "Файл не должен превышать 20Мб",
				"errors" => ["Файл не должен превышать 20Мб"]
			));
		}

?>