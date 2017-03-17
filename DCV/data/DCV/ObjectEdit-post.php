<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('DisputCadastralValue');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
	print_r($adata);
	
	/*
	
	if( (int)$adata->Osnovanie >0 ){ // Выбрано значение из списка
		$Osnovanie_id = $adata->Osnovanie;
	}else{ // Нужно ввести новое значение
		if( trim($adata->Osnovanie)<>'' ){
			$cnt = $db->getOne("SELECT count(*) FROM `ref_PetitionOsnovaniya` WHERE name='$adata->Osnovanie'");
			if( $cnt == 0 ){
				$sql = "INSERT INTO `ref_PetitionOsnovaniya` SET name='$adata->Osnovanie'";
				$db->query($sql);
				$Osnovanie_id = $db->insertId();
			}
		}
	}
	*/
	
	if($adata->id == 0){ // Добавляем
	
	//	$db->query("INSERT INTO `GZC#Effectives` SET Punkt={$adata->Punkt}, Percent1={$adata->Percent1}, Percent2={$adata->Percent2}, Effective={$adata->Effective}");

	//	$ID= $db->insertId();
		$ID= 100000;
	
	} else { // Исправляем
	//	$db->query("UPDATE  `GZC#Effectives` SET Punkt={$adata->Punkt}, Percent1={$adata->Percent1}, Percent2={$adata->Percent2}, Effective={$adata->Effective} WHERE id={$adata->id}");

		$ID= $adata->id;

	}
	
		
		echo json_encode(array('success'=>'true', 'data'=>$ID)); //,'data'=>$data));
	
	
	
?>