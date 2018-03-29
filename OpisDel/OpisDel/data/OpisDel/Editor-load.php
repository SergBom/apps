<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	//$_SESSION['portal']['user_id']
	//$_SESSION['portal']['username']
	//$_SESSION['portal']['FIO']
	//$_SESSION['portal']['otdel_id']

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('OpisDel');
/*---------------------------------------------------------------------------*/

	//$data = array();
	//$info = $_POST['data'];
	//$adata = json_decode($info);
	//print_r($adata);
	
	
	
		// Находим корневой Отдел пользователя
		$otdel_par_id = $db->getOne("SELECT par_id FROM `portal`.`prt#otdels` WHERE id={$_SESSION['portal']['otdel_id']}");
		if( $otdel_par_id <> 0){
			$res_otdel = $db->query("SELECT id, name FROM `portal`.`prt#otdels` WHERE id={$otdel_par_id}");
		} else {
			$res_otdel = $db->query("SELECT id, name FROM `portal`.`prt#otdels` WHERE id={$_SESSION['portal']['otdel_id']}");
		}
		$row_otdel = mysqli_fetch_array($res_otdel);
		
	
	 
		$count = $db->getOne("SELECT count(*) FROM Opis");
		if($count == 0){ $Npp=1; }
		else {
			$Npp = $db->getOne("SELECT Npp FROM Opis ORDER BY id desc LIMIT 1") + 1;	 
		}
	 
	 

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"otdel" => $row_otdel['id'],
				"otdel_name" => $row_otdel['name'],
				"Npp" => $Npp
			)
		));
	
?>