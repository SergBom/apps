<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$user_id  = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$user_fio = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO']  : '';

	$rec_guid = trim ((!empty($_POST['REC_GUID'])) ? $_POST['REC_GUID'] : "" ); // 
	$status  = trim ((!empty($_POST['STATUS_ERROR'])) ? $_POST['STATUS_ERROR'] : "0" );
	$user_comment  = trim ((!empty($_POST['USER_COMMENT'])) ? $_POST['USER_COMMENT'] : "" );
	
/*---------------------------------------------------------------------------*/

	$conn = ConnectLocalTIR(); // Присоска к базе

	// Обновляем T$FNS#DOCUMENT_RES_LOG по конкретной записи
	$sql = "UPDATE T\$FNS#DOCUMENT_RES_LOG SET 
				 USER_ID='$user_id'
				,USER_NAME='$user_fio'
				,USER_DATE= SYSDATE 
				,USER_COMMENT='$user_comment'
				,STATUS_ERROR=$status
	WHERE REC_GUID='$rec_guid'";
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


	//echo $sql;

		echo json_encode(array('success'=>'true'));
?>