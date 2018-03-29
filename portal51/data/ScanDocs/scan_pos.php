<?php
#header('Content-type: text/html; charset=utf-8');
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init.php");
	$cr = "\n";
	//include_once("/var/www/portal/public_html/php/ldap/ldap-func.php");
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
	//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
	$cr = "<br>";
}


/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/


    $db  = ConnectOciDB('EGRP');
    $dbS = ConnectMyDB('Scan_docs');
	
/*	$sqlS = "SELECT SQL_CALC_FOUND_ROWS  id, replace(name,' ',':') name FROM docs2 AS c
		WHERE 1=1
			and par_id =0
			AND name  regexp '51 [0-9][0-9] [0-9][0-9][0-9][0-9][0-9][0-9][0-9] [0-9].*'";
	*/		
$sqlS = "select id, replace(name,' ',':') name from `docs2` p2 where p2.tp=1";
//where name like '51 10 %'";
			
//	echo date();
	if ( $result = $dbS->query( $sqlS ) ) {

	//echo "<table border='1' cellspacing='0' cellpadding='1'>\n";
		$n = 0;
		$c = 0;
		$total_rows=$dbS->getOne("select FOUND_ROWS()");
		while ($rowS = $result->fetch_assoc()) {
			$c++;

			$sql = "select count(*) CNT from cse_cases cc, re_objects ro
where CC.R_TYPE = 'П' and ro.id = cc.re_id
and exists(select 1 from cse_folders cf, cse_doc cd where CF.ID = CD.CSF_ID and CF.CSE_ID = cc.id)
AND ( ro.CAD_NUM = '{$rowS['name']}' OR ro.OBJ_NUM = '{$rowS['name']}')";
			
			//echo $sql.$cr;
			$stid = oci_parse($db, $sql);
			oci_execute($stid);
			$row = oci_fetch_array($stid);
			
			//echo "-------------- ".$row['CNT'].$cr;
			
			
			if( intval($row['CNT']) == 1 ){		$n++;
//				echo $rowS['name']."  ==> ".$row['CNT']."<br>";
			}
			
			echo "[$n] из [$c]  ==>  ". $rowS['name']."  =====> ".$row['CNT'].$cr;
			$dbS->query( "UPDATE docs2 SET opis={$row['CNT']} WHERE id={$rowS['id']}" );
			
		}
		echo "Всего количество дел с описями: $n из $c $cr";
		
		
		
		//$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
		//echo json_encode($c);
	}
	

	
?>