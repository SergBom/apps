<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();


	$db = ConnectPDO('CadastErrors');
/*---------------------------------------------------------------------------*/
	$data = array();
	$a = (object)$_REQUEST;
	
	//print_r($a);
	//print_r($_SESSION['portal']);
	
//	$dateOtchet = substr($adata->dateOtchet,0,10);
	//echo "User_id='$user_id'<br>";

for ($i=2; $i<=69; $i++){
		$db->query("INSERT INTO `ParamsErrors` SET group_id=2, FieldName='f.2.$i', FieldRefer='п.2.$i'");
	
}

		
?>