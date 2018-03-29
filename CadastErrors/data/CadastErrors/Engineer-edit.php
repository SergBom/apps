<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	//@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//@$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	//$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	//$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	//$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';

/*---------------------------------------------------------------------------*/
	$db = ConnectPDO('CadastErrors');
/*---------------------------------------------------------------------------*/
	$data = array();
	$a = (object)$_REQUEST;
	
	//print_r($_SESSION['portal']);
	
	$a->Fm = trim($a->Fm);
	$a->Im = trim($a->Im);
	$a->Ot = trim($a->Ot);
	$a->AttNumber = trim($a->AttNumber);
	
$sql = "Fm='{$a->Fm}',Im='{$a->Im}',Ot='{$a->Ot}',AttNumber='{$a->AttNumber}', update_user='$user_id' ";
	
	
if( $a->id == 0 ){
	$sql = "INSERT INTO `Engineers` SET " . $sql;
} else {
	$sql = "UPDATE `Engineers` SET " . $sql . " WHERE id={$a->id}";
}
	
	$db->query($sql);
		
	echo json_encode(array('success'=>'true','data'=>$sql));
		
?>