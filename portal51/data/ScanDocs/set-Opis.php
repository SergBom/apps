<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
//$CAD_NUM = str_replace(" ",":",$_POST['name']);
/*---------------------------------------------------------------------------*/


    $db  = ConnectOciDB('EGRP');
	//$db = new PDO('oci:dbname=j51cdb;charset=AL32UTF8','reg_rt','reg32');
	
    $pdo = ConnectMyPDO('Scan_docs');


	$stm = $pdo->query( "SELECT replace(name,' ',':') name FROM docs2 WHERE tp=1" );
	//print_r($name);
	
	while( $r=$stm->fetch() ){
		$sql = "select count(*) CNT from cse_cases cc, re_objects ro
				where CC.R_TYPE = 'П' and ro.id = cc.re_id
				and exists(select 1 from cse_folders cf, cse_doc cd where CF.ID = CD.CSF_ID and CF.CSE_ID = cc.id)
				AND ( ro.CAD_NUM = '{$r['name']}' OR ro.OBJ_NUM = '{$r['name']}')";
		
			//echo $sql.$cr;
		$stid = oci_parse($db, $sql);	oci_execute($stid);
		$row = oci_fetch_array($stid);
	
		//echo $r['name']."  => {$row['CNT']}$cr";
	
	//	$pdo->query( "UPDATE docs2 SET opis={$row['CNT']} WHERE id={$_POST['id']}" );
	}
	
	
		
?>