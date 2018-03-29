<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//print_r($_SESSION);

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;

	$category_id = $db->getOne( "SELECT id FROM tabFAQcategory WHERE category='{$adata->category}'" );
	
	if($category_id){
	}else{
		@$db->query("INSERT INTO tabFAQcategory SET category='{$adata->category}'");
		$category_id = $db->insertId();
	}

	
	if($adata->id == 0){ // Добавляем
	
	$sql = "INSERT INTO tabFAQ SET
				quest='{$adata->quest}',
				answer='{$adata->answer}',
				category_id=$category_id,
				insertUser='{$_SESSION['portal']['user_id']}'";
		@$db->query($sql);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				"quest"=>"'{$adata->quest}'",
				"answer"=>"'{$adata->answer}'",
				"category"=>"'{$adata->category}'",
				"insertUser"=>"'{$_SESSION['portal']['user_id']}'"
			)
		));
		
	} else { // Исправляем
		$sql = "UPDATE tabFAQ SET
				quest='{$adata->quest}',
				answer='{$adata->answer}',
				category_id=$category_id,
				insertUser='{$_SESSION['portal']['user_id']}'
				WHERE id={$adata->id}";
		@$db->query($sql);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				"quest"=>"'{$adata->quest}'",
				"answer"=>"'{$adata->answer}'",
				"category"=>"'{$adata->category}'",
				"updateUser"=>"'{$_SESSION['portal']['user_id']}'"
			)
		));
		
	}
?>