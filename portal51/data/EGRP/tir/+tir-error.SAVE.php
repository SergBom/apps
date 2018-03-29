<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$rec_guid = trim ((!empty($_POST['rec_guid'])) ? $_POST['rec_guid'] : "" ); // 
$status  = trim ((!empty($_POST['status_error'])) ? $_POST['status_error'] : "0" );
$userGID  = trim ((!empty($_POST['userGID'])) ? $_POST['userGID'] : "" );
$userName  = trim ((!empty($_POST['userName'])) ? $_POST['userName'] : "" );
$user_comment  = trim ((!empty($_POST['user_comment'])) ? $_POST['user_comment'] : "" );
$kladr_need  =  !empty($_POST['kladr_need']) ? 1 : 0;
$kladr_yes   =  !empty($_POST['kladr_yes']) ? 1 : 0;
/*---------------------------------------------------------------------------*/

	$conn = ConnectLocalTIR(); // Присоска к базе

	// Обновляем T$FNS#DOCUMENT_FLK_LOG по конкретной записи
	$sql = "UPDATE T\$FNS#DOCUMENT_FLK_LOG SET 
				 USER_ID='$userGID'
				,USER_NAME='$userName'
				,USER_DATE= SYSDATE 
				,USER_COMMENT='$user_comment'
				,STATUS_ERROR=$status
				,KLADR_NEED=$kladr_need
				,KLADR_YES=$kladr_yes
	WHERE REC_GUID='$rec_guid'";
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);


	//echo $sql;

//	if($stdi){
		echo json_encode(array('success'=>'true'));
//	} else {
//		echo json_encode(array('failure'=>'true'));
//	}

//@oci_free_statement($stid);
//@oci_close($conn);
?>