<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] )       ?  $_SESSION['portal']['FIO']       : '';
	
	$a = json_decode($_REQUEST['data']);

/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/


	//var_dump($a);


	$sql = "UPDATE `docs_l2` SET 
		`cyear`='{$a->cyear}',
		`name`='{$a->name}',
		`cdate`='{$a->cdate}',
		`cnt_lists`={$a->cnt_lists}
		,user_id='$user_id'
		,user_fio='$user_FIO'
		WHERE id={$a->id}";
		
	$db->query( $sql );

	
//	$sql = "select * from `docs_l2` where id_l1={$a->id}";
	
//	$data = $db->query(	$sql )->fetchAll();

	echo json_encode(array('success'=>'true',$_REQUEST['data']));	

?>