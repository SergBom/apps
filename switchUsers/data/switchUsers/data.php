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
//	$dbora	= ConnectGRP();
	

if($id=="root")	{
	$w = "";
} else {
	$w = "WHERE otdel_id='$id'";
}

	$stP = $db->query("SELECT * FROM v_users_switch $w ORDER BY userFm,userIm,userOt");



$data = array();			
while(($rP = $stP->fetch()) !== false ){

/*	$sn = $rP['userFm'].mb_substr($rP['userIm'],0,1,'utf-8').".".mb_substr($rP['userOt'],0,1,'utf-8').".";
	$stdi = oci_parse($dbora,"SELECT id,name,short_name,username FROM rp_emps WHERE E_DATE is NULL AND replace(short_name,' ')='$sn'");
	oci_execute($stdi);
	
	while(($row = oci_fetch_assoc($stdi)) !== false ){
		$rP['egrp'][] = $row ; 
	}  
*/

	$data[] = $rP;
	
}  

			echo json_encode(Array(
				"success"=>"true",
				"data"=>$data
			));
					
///////////////////////////////
//
// SELECT username, account_status, lock_date FROM dba_users WHERE account_status = 'OPEN'
//
?>