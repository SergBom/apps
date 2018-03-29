<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	$strSQL = "select * FROM `portal`.`prt#v#groups_add`";
	
	$strSQL .= ( isset($_GET['par_id']) ) ? " WHERE par_id={$_GET['par_id']}" : "";

	$result = $link->query($strSQL);

	$data = array();

while($row = mysqli_fetch_array($result)) {
   array_push($data, $row);
}
$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
 
?>


