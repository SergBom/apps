<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

//print_r($_POST);
 
	$user_id  = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$user_fio = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO']  : '';

	
	$user_comment  = trim ((!empty($_POST['USER_COMMENT'])) ? $_POST['USER_COMMENT'] : "" );
	$status		   = trim ((!empty($_POST['STATUS_ERROR'])) ? $_POST['STATUS_ERROR'] : "0" );
	$kladr_need  =  !empty($_POST['kladr_need']) ? 1 : 0;
	$kladr_yes   =  !empty($_POST['kladr_yes']) ? 1 : 0;

/*---------------------------------------------------------------------------*/
/*
*/
	$conn = ConnectLocalTIR(); // Присоска к базе

		$sql = "UPDATE T\$FNS#DOCUMENT_FLK_LOG SET 
				 USER_ID='$user_id'
				,USER_NAME='$user_fio'
				,USER_DATE= SYSDATE 
				,USER_COMMENT='$user_comment'
				,STATUS_ERROR=$status
				,KLADR_NEED=$kladr_need
				,KLADR_YES=$kladr_yes
		WHERE REC_GUID='{$_POST['REC_GUID']}'";
	//}
	
	// Обновляем T$FNS#DOCUMENT_FLK_LOG по конкретной записи
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);

	//echo $sql;

	echo json_encode(array('success'=>'true'));
?>