<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
$app_class = "evaleffectives";
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
//	print_r($adata);
	
	$user_id = 	is_numeric($adata->user)? $adata->user : $adata->user_id ;
	//$user_id = 	(int)$adata->user;
	$d_id = 	is_numeric($adata->groupname) ? $adata->groupname : $adata->d_id;
	
	$result=$db->query("select concat(userFm,' ',userIm,' ',userOt) FIO, otdel_id from `portal`.`prt#users` tu where tu.id='$user_id'");
	$row = $result->fetch_assoc();
	
	
	
	if($adata->id == 0){ // Добавляем
	
		$sql = "INSERT INTO `GZC#Jobers` SET user_id={$user_id}, d_id={$d_id}, FIO='{$row['FIO']}', otdel_id='{$row['otdel_id']}'";
		//echo $sql;
		$db->query($sql);

		$ID= $db->insertId();
		
		
	} else { // Исправляем
		$db->query("UPDATE `GZC#Jobers` SET user_id={$user_id}, d_id={$d_id}, FIO='{$row['FIO']}', otdel_id='{$row['otdel_id']}' WHERE id={$adata->id}");

		$ID= $adata->id;

	}


		$strSQL = "CALL `portal`.`proc#user_add_group`($user_id, '$app_class'); ";
		@$db->query($strSQL);

	
	$sql = "select * from `GZC#v#Jobers` where id=$ID";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}
		
		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>