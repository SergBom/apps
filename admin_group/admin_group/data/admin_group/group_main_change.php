<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$strSQL = "SELECT main_group  FROM `portal`.`prt#users` WHERE id={$_POST['id']}";
$u_id = $link->getOne($strSQL);
$u_id++;
if($u_id>3){$u_id=1;}

$strSQL = "UPDATE `portal`.`prt#users` SET main_group=$u_id   WHERE id={$_POST['id']}";
$result = $link->query($strSQL);

$c = array('success'=>0);
echo json_encode($c);

?>


