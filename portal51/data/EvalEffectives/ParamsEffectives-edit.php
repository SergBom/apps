<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
	
	if($adata->id == 0){ // Добавляем
		$db->query("INSERT INTO `GZC#Effectives` SET Punkt={$adata->Punkt}, Percent1={$adata->Percent1}, Percent2={$adata->Percent2}, Effective={$adata->Effective}");

		$ID= $db->insertId();
	
	} else { // Исправляем
		$db->query("UPDATE  `GZC#Effectives` SET Punkt={$adata->Punkt}, Percent1={$adata->Percent1}, Percent2={$adata->Percent2}, Effective={$adata->Effective} WHERE id={$adata->id}");

		$ID= $adata->id;

	}
	
		
		echo json_encode(array('success'=>'true')); //,'data'=>$data));
	
	
	
?>