<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	//$strSQL = "SELECT id, name, app_nik  FROM `portal`.`prt#groups` ORDER  by id";
	$strSQL = "select * FROM `prt#v#groups_main`";
$result = $link->query($strSQL);

$data = array();


while($row = mysqli_fetch_array($result)) {
   array_push($data, $row);
}
$c = array('success'=>0,'data'=>$data);
echo json_encode($c);
 
?>


