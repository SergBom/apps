<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/


$_POST['app_nik'] = ( $_POST['app_nik'] <> "" ) ? $_POST['app_nik']: "gr".mt_rand(10000, 99999);


if($_POST['id']==0){ //Новая запись
	
	$strSQL = "INSERT INTO `prt#groups` SET name='{$_POST['name']}', reference='{$_POST['reference']}', app_nik='{$_POST['app_nik']}',
		par_id={$_POST['par_id']}";

} else { // Обновление записи

	$strSQL = "UPDATE `prt#groups` SET name='{$_POST['name']}', reference='{$_POST['reference']}', app_nik='{$_POST['app_nik']}'
		WHERE id={$_POST['id']}";

}

$result = $link->query($strSQL);

echo json_encode(array(
				'success' => mysql_errno()==0,
				'data' => $_POST
				));
?>


