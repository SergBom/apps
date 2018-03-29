<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');



	$d1 = trim ((!empty($_POST['db'])) ? $_POST['db'] : date('Y-m-d') ); // 
	$d2 = trim ((!empty($_POST['de'])) ? $_POST['de'] : date('Y-m-d') ); // 

    $db = ConnectMyDB('EvalEffective');
	$sql = "SELECT `portal`.`func#jcal_wd`('$d1', '$d2','h')";
	//echo $sql;
	$Q_rab  = $db->getOne( $sql);
	
	echo json_encode(array('success'=>'true','data'=>array('db'=>$d1,'de'=>$d2,'Q_rab'=>"$Q_rab")));
	

?>
