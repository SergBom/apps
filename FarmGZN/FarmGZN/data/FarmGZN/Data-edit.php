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
	$db = ConnectPDO('FarmGZN');
/*---------------------------------------------------------------------------*/
	$data = array();
	$a = (object)array_map('a_trim',$_REQUEST);

	$error_type = isset($a->error_type) ? 1: 0;
	$now = date("Y-m-d");
	
	$prov_form_1 = ( $a->prov_form_1 <> '' )?  ",`prov_form_1`='{$a->prov_form_1}'" : "";
	$prov_form_2 = ( $a->prov_form_2 <> '' )?  ",`prov_form_2`='{$a->prov_form_2}'" : "";
	$prov_date_end 	= ( $a->prov_date_end <> '' )?  ",`prov_date_end`='{$a->prov_date_end}'" : "";
	$prov_akt_date 	= ( $a->prov_akt_date <> '' )?  ",`prov_akt_date`='{$a->prov_akt_date}'" : "";
	$prov_pred_date = ( $a->prov_pred_date <> '' )?  ",`prov_pred_date`='{$a->prov_pred_date}'" : "";
	$prov_pred_srok = ( $a->prov_pred_srok <> '' )?  ",`prov_pred_srok`='{$a->prov_pred_srok}'" : "";
	$edoc_date		= ( $a->edoc_date <> '' )?  ",`edoc_date`='{$a->edoc_date}'" : "";
	$area		 	= ( $a->area <> '' ) ? ",`area`='{$a->area}'" : "";
	
	
$set = "`cad_num`='{$a->cad_num}',
`address`='{$a->address}',
`otdel_id`='{$a->otdel}',
`job_id`='{$a->job}',
`prov_akt_num`='{$a->prov_akt_num}',
`prov_pred_num`='{$a->prov_pred_num}',
`error_vid`='{$a->error_vid}',
`oborot`='{$a->oborot}',
`error_type`={$error_type},
`organ_id`='{$a->organ}',
`edoc_num`='{$a->edoc_num}',
`update_user`='$user_id'
$prov_form_1
$prov_form_2
$prov_date_end
$prov_akt_date
$prov_pred_date
$prov_pred_srok
$edoc_date
$area
";

			
			

if( $a->id == 0 ){
	$sql = "INSERT INTO `Errors` SET $set,`dateIn`='{$now}',user_id='$user_id',user_fio='$user_FIO'";

}else{
	$sql = "UPDATE `Errors` SET $set WHERE id={$a->id}";
	
}


	
	//echo "$sql<br>";
	
	$db->query($sql);

	
	echo json_encode(array('success'=>'true','data'=>$sql));
	


function a_trim($value){
	return trim($value);
}
	
?>