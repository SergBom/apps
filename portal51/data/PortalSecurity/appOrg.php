<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	$user_id = isset( $_SESSION['portal']['user_id'] ) ? $_SESSION['portal']['user_id'] : '';
	$org_id = isset( $_SESSION['portal']['org_id'] ) ? $_SESSION['portal']['org_id'] : '';
	$org_name = isset( $_SESSION['portal']['org_name'] ) ? $_SESSION['portal']['org_name'] : '';
/*---------------------------------------------------------------------------*/
    //$db = ConnectPDO('portal');
/*---------------------------------------------------------------------------*/
	$result = array();

	$appClassName = ($_REQUEST['appClassName']) ? $_REQUEST['appClassName'] : '';

if( $appClassName ){
	
	$result = array('id'=>$org_id,'name'=>$org_name);
	
}
	
echo json_encode($result);
?>
