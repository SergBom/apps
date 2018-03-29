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

	$stid = oci_parse($conn, "SELECT USER_NAME FROM T\$FNS#DOCUMENT_FLK_LOG WHERE REC_GUID='$rec_guid'");
    oci_execute($stid);
	$row = oci_fetch_array($stid);
	
	//echo 'UN='.$row['USER_NAME'];
	
	if($row['USER_NAME']){
		$sql = "UPDATE T\$FNS#DOCUMENT_FLK_LOG SET 
				 USER_ID_UPDATE='$userGID'
				,USER_NAME_UPDATE='$userName'
				,USER_DATE_UPDATE= SYSDATE 
				,USER_COMMENT='$user_comment'
				,STATUS_ERROR=$status
				,KLADR_NEED=$kladr_need
				,KLADR_YES=$kladr_yes
		WHERE REC_GUID='$rec_guid'";
	} else {
		$sql = "UPDATE T\$FNS#DOCUMENT_FLK_LOG SET 
				 USER_ID='$userGID'
				,USER_NAME='$userName'
				,USER_DATE= SYSDATE 
				,USER_COMMENT='$user_comment'
				,STATUS_ERROR=$status
				,KLADR_NEED=$kladr_need
				,KLADR_YES=$kladr_yes
		WHERE REC_GUID='$rec_guid'";
	}
	
	// Обновляем T$FNS#DOCUMENT_FLK_LOG по конкретной записи
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);

	//echo $sql;

	echo json_encode(array('success'=>'true'));

//@oci_free_statement($stid);
//@oci_close($conn);
?>