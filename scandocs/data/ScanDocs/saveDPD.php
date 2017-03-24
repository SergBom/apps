<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init2.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/

    $pdo = ConnectPDO('Scan_docs');

$dpd_list = explode("\n",$_POST['dpd_list']);



$count   	= 0;
$count_bad	= 0;
$err_dpd 	= "";

foreach( $dpd_list as $dpd ){
	
	$dpd = str_replace(':',' ',trim($dpd));
	if( $dpd ){
	
		if( preg_match("/^\d\d \d\d \d\d\d\d\d\d\d \d+$/", $dpd) ){

			$c = $pdo->query("SELECT count(*) FROM docs2 WHERE `name`='$dpd'")->fetchColumn();
			if( $c ){
				//$sql  = "UPDATE docs2 ";
				//$sql2 = "WHERE";
				$count_bad++;
				$err_dpd .= $dpd . " - Дубликат" . $cr;
			} else {
				$sql = "INSERT INTO docs2 SET 
					`par_id`=0,
					`name`='$dpd',
					`tp`=1,
					`cdate`='{$_POST['dpd_date']}',
					`opis`=1,
					`retro`=2";
					//ON DUPLICATE KEY UPDATE `name`='$dpd'";
		
					//echo $sql.$cr;
					$pdo->query( $sql );
					$count++;
			}
		} else {
			$count_bad++;
			$err_dpd .= $dpd . " - Не верный формат" . $cr;
		}
		
	} 
	
}

	$msg = "Внесено дел = $count.<br>С ошибками дел = $count_bad.";
	if( $count_bad ){
		$msg .= "<p>Следующие записи с ошибками:<br>".$err_dpd."</p>";
	}


	$c = array('success'=>true,'msg'=>$msg);
	echo json_encode($c);
	





/*
	
	
	$sql = "select count(*) CNT from cse_cases cc, re_objects ro
where CC.R_TYPE = 'П' and ro.id = cc.re_id
and exists(select 1 from cse_folders cf, cse_doc cd where CF.ID = CD.CSF_ID and CF.CSE_ID = cc.id)
AND ( ro.CAD_NUM = '$CAD_NUM' OR ro.OBJ_NUM = '$CAD_NUM')";
			
			//echo $sql.$cr;
	$stid = oci_parse($db, $sql);	oci_execute($stid);
	$row = oci_fetch_array($stid);
	$cnt = $row['CNT'];
	
	$pdo->query( "UPDATE docs2 SET opis=$cnt WHERE id={$_POST['id']}" );
		
	$c = array('success'=>0,'opis'=>$cnt);
	echo json_encode($c);
	
	*/
?>