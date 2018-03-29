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

	
	$KU_date_uchet = ( $a->KU_date_uchet <> '' )?  ",KU_date_uchet='{$a->KU_date_uchet}'" : "";
	$KU_date_none = ( $a->KU_date_none <> '' )?  ",KU_date_none='{$a->KU_date_none}'" : "";
	$KU_date_stop = ( $a->KU_date_stop <> '' )?  ",KU_date_stop='{$a->KU_date_stop}'" : "";
	$KU_FZ = ( $a->KU_FZ <> '' )?  ",KU_FZ='{$a->KU_FZ}'" : "";
	$KU_treb = ( $a->KU_treb <> '' )?  ",KU_treb='{$a->KU_treb}'" : "";
	$KU_refer = ( $a->KU_refer <> '' )?  ",KU_refer='{$a->KU_refer}'" : "";
	
	
$set = "CadEngineer='{$a->CadEngineer}',
charc='{$a->charc}',
ONvid='{$a->ONvid}',
zayav='{$a->zayav}',
zayav_date='{$a->zayav_date}',
zayav_type='{$a->zayav_type}',
KU_user_id='$user_id',
KU_user_fio='$user_FIO',
KU_user_update='$now'
$KU_date_uchet
$KU_date_none
$KU_date_stop
$KU_FZ
$KU_treb
$KU_refer
";

			
			

if( $a->id == 0 ){
	$sql = "INSERT INTO `data` SET $set";

}else{
	$sql = "UPDATE `data` SET $set WHERE id={$a->id}";
	
}

	
	//echo "$sql<br>";
	
	$db->query($sql);

	
	echo json_encode(array('success'=>'true','data'=>$sql));
	


function a_trim($value){
	return trim($value);
}
	
?>