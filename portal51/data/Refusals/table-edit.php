<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//$uploaddir = $_SERVER["DOCUMENT_ROOT"].'apps/refusals/data/Refusals/files/';
$uploaddir = "files/";

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_gzn');
/*---------------------------------------------------------------------------*/

	$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];

	//print_r($adata);
	
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	//$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;


	$adata = (object)$_POST;
	$id = $adata->id;
	
if(	$id==0 ){ // Add
	$db->query("INSERT INTO `refusals` SET
			cad_num='".trim($adata->cad_num)."',
			address='".trim($adata->address)."',
			reference='".trim($adata->reference)."',
			userInsert='$user_id',
			userUpdate='$user_id'"
		);		
	$id = $db->insertId();
} else { // Edit
	$db->query("UPDATE `refusals` SET
			cad_num='".trim($adata->cad_num)."',
			address='".trim($adata->address)."',
			reference='".trim($adata->reference)."',
			userUpdate='$user_id'
			WHERE id=$id"
		);		

}



        $file = @$_FILES['document'];
        $fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
		$fileType = $file['type'];
		$fileType2 = substr(strrchr($fileName, '.'), 1);
        if (!$fileSize) {
            $fileSize = $_SERVER['CONTENT_LENGTH'];
        }

		if( $fileSize <= 20000000 ){
			/*$db->query("INSERT INTO `refusals` SET
					cad_num='".trim($adata->cad_num)."',
					address='".trim($adata->address)."',
					reference='".trim($adata->reference)."'"
				);		
	
			$id = $db->insertId();

			*/
			$fileDoc = "doc_Refus_".str_pad($id, 5, "0", STR_PAD_LEFT).".$fileType2";
			if( move_uploaded_file($fileTmpName, $uploaddir.$fileDoc ) )  {			
			//if( rename($fileTmpName, $uploaddir.$fileDoc ) ){
				$db->query("UPDATE `refusals` SET document='$fileDoc', fileName='$fileName'	WHERE id=$id");
			}
			
			echo json_encode(array(
				"success" => true,
				"fileName" => $fileName,
				//"fileSize" => $fileSize,
				//"CWD" => getcwd(),
				//"type" => $fileType2,
				//"to" => $uploaddir.$fileDoc,
				//"tmp" => $fileTmpName
				"message" => "Файл загружен в хранилище"
			));
			
		} else {
			echo json_encode(array(
				"success" => false,
				"message" => "Файл не должен превышать 20Мб"
			));
		}

?>