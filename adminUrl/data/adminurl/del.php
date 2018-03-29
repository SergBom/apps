<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
$id  = isset($_POST['id'])  ? $_POST['id']  :  "";
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/
 
$strSQL = "delete from tabMainLinks   WHERE parentid = $id";
$result = $link->query($strSQL);

$strSQL = "delete from tabMainLinks   WHERE id = $id";
$result = $link->query($strSQL);
	   
	 
	?>