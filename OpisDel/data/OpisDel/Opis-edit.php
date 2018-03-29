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
	$info = $_POST['data'];
	$adata = json_decode($info);
	//print_r($adata);

	
	if( isset($adata->otdel) ){
		$row_otdel['id'] = $adata->otdel;
		$row_otdel['name'] = $db->getOne("SELECT replace(name, 'Мур.область ', '') as name FROM `portal`.`prt#otdels` WHERE id={$adata->otdel}");
		//print_r($row_otdel);
		
	}else{
	
		// Находим корневой Отдел пользователя
		$otdel_par_id = $db->getOne("SELECT par_id FROM `portal`.`prt#otdels` WHERE id={$_SESSION['portal']['otdel_id']}");
		if( $otdel_par_id <> 0){
			$res_otdel = $db->query("SELECT id, replace(name, 'Мур.область ', '') as name FROM `portal`.`prt#otdels` WHERE id={$otdel_par_id}");
		} else {
			$res_otdel = $db->query("SELECT id, replace(name, 'Мур.область ', '') as name FROM `portal`.`prt#otdels` WHERE id={$_SESSION['portal']['otdel_id']}");
		}
		$row_otdel = mysqli_fetch_array($res_otdel);
	}

	
	if($adata->id == 0){ // Добавляем
	
	
		$Npp = $db->getOne("SELECT IF( (SELECT count(*) FROM Opis WHERE Npp='{$adata->Npp}' LIMIT 1)=0,'{$adata->Npp}',
				IFNULL((SELECT Npp FROM Opis ORDER BY id desc LIMIT 1)+1,1)
				) as Npp");
		
	 //Npp={$Npp},

		$sql = "INSERT INTO Opis SET
			
			`Index`='".trim($adata->Index)."',
			TitleBook='".trim($adata->TitleBook)."',
			TitleNumber='".trim($adata->TitleNumber)."',
			Year={$adata->Year},
			DateBegin='{$adata->DateBegin}',
			DateEnd='{$adata->DateEnd}',
			Listov='".trim($adata->Listov)."',
			Refer='".trim($adata->Refer)."',
			InsertOID='".$row_otdel["id"]."',
			InsertOtdel='".$row_otdel["name"]."',
			InsertFIO='".$_SESSION['portal']['FIO']."',
			InsertUID='".$_SESSION['portal']['user_id']."'
			
			";
			//echo $sql;
		$db->query($sql);

		$data = (array)$adata;
		$data['id'] = $db->insertId();
		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => $data
		));
		
	} else { // Исправляем
	///**///Npp={$Npp},
	 	$sql = "UPDATE Opis SET
			`Index`='".trim($adata->Index)."',
			TitleBook='".trim($adata->TitleBook)."',
			TitleNumber='".trim($adata->TitleNumber)."',
			Year={$adata->Year},
			DateBegin='{$adata->DateBegin}',
			DateEnd='{$adata->DateEnd}',
			Listov='".trim($adata->Listov)."',
			Refer='".trim($adata->Refer)."',
			InsertOID='".$row_otdel["id"]."',
			InsertOtdel='".$row_otdel["name"]."',
			InsertFIO='".$_SESSION['portal']['FIO']."',
			InsertUID='".$_SESSION['portal']['user_id']."'
			
			WHERE id={$adata->id}
			";
			//echo $sql;
		$db->query($sql);

		$data = (array)$adata;
		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => $data
		));
	}
	
?>