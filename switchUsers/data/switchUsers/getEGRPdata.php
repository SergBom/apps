<?php
header('Content-type: text/html; charset=utf-8');
$file_init = "/php/init2.php";
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html".$file_init);
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}".$file_init);
}

$id = $_GET['id'];



	$db		= ConnectPDO('Portal');
	
	$rP = $db->query("SELECT userFm,userIm,userOt FROM `prt#users` WHERE id='$id'")->fetch();

	$dbora	= ConnectGRP();
	


	$sn = $rP['userFm'].mb_substr($rP['userIm'],0,1,'utf-8').".".mb_substr($rP['userOt'],0,1,'utf-8').".";

	$stdi = oci_parse($dbora,"SELECT id,name,short_name,username FROM rp_emps WHERE E_DATE is NULL AND replace(short_name,' ')='$sn'");
	oci_execute($stdi);

	$row1 = oci_fetch_assoc($stdi);
	
	$stdi = oci_parse($dbora,"SELECT username, account_status, lock_date, EXPIRY_DATE, CREATED FROM dba_users WHERE username='{$row1['USERNAME']}'");
	oci_execute($stdi);

	$row2 = oci_fetch_assoc($stdi);
	

			echo json_encode( array(
				"success"=>"true",
				"data"=>$row
			));

///////////////////////////////
//
// SELECT username, account_status, lock_date, EXPIRY_DATE, CREATED FROM dba_users WHERE account_status = 'OPEN'
//
// account_status: 
// OPEN
// EXPIRED
// LOCKED
// LOCKED(TIMED)
// EXPIRED & LOCKED(TIMED)
// EXPIRED & LOCKED
?>