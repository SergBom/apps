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
	$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';

/*---------------------------------------------------------------------------*/
	$db = ConnectPDO('GKUstop');
/*---------------------------------------------------------------------------*/
	$data = array();
	$a = (object)array_map('a_trim',$_REQUEST);

	$now = date("Y-m-d");

	
	$RR_date_uchet = ( $a->RR_date_uchet <> '' )?  ",RR_date_uchet='{$a->RR_date_uchet}'" : "";
	$RR_date_none = ( $a->RR_date_none <> '' )?  ",RR_date_none='{$a->RR_date_none}'" : "";
	$RR_date_stop = ( $a->RR_date_stop <> '' )?  ",RR_date_stop='{$a->RR_date_stop}'" : "";
	$RR_FZ = ( $a->RR_FZ <> '' )?  ",RR_FZ='{$a->RR_FZ}'" : "";
	$RR_treb = ( $a->RR_treb <> '' )?  ",RR_treb='{$a->RR_treb}'" : "";
	$RR_refer = ( $a->RR_refer <> '' )?  ",RR_refer='{$a->RR_refer}'" : "";
	
	
$set = "RR_user_id='$user_id',
RR_user_fio='$user_FIO',
RR_user_update='$now'
$RR_date_uchet
$RR_date_none
$RR_date_stop
$RR_FZ
$RR_treb
$RR_refer
";

			


	$sql = "UPDATE `data` SET $set WHERE id={$a->id}";
	
	//echo "$sql<br>";
	
	$db->query($sql);

	
	echo json_encode(array('success'=>'true','data'=>$sql));
	


function a_trim($value){
	return trim($value);
}
	
?>