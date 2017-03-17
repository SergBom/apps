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
//count(*) AS cnt, 
			$sql = "SELECT 
 ro.CAD_NUM as CDN, ro.OBJ_NUM as OBN
from cse_cases cc, re_objects ro
where CC.R_TYPE = 'П'
and ro.id = cc.re_id
and exists(select 1 from cse_folders cf, cse_doc cd where CF.ID = CD.CSF_ID and CF.CSE_ID = cc.id)";
//AND ( ro.CAD_NUM = '{$rowS['name']}' OR ro.OBJ_NUM = '{$rowS['name']}')";
			
			echo $sql.$cr;
			$db  = ConnectOciDB('EGRP');
			$stid = oci_sql_exec($db, $sql);
			//$row = oci_fetch_array($stid);
			//$row = oci_fetch_array($stid);
			while($row = oci_fetch_array($stid)){
			
			echo "-------------- ".$row['CDN']."   ".$row['OBN'].$cr;
			
			}
			//if( intval($row['CNT']) == 1 ){
				//$n++;
//				echo $rowS['name']."  ==> ".$row['CNT']."<br>";
			//}
	//		echo "[$n] из [$c]  ==>  ". $rowS['name']."  =====> ".$row['CNT'].$cr.$cr;
//			$dbS->query( "UPDATE docs2 SET opis={$row['CNT']} WHERE id={$rowS['id']}" );
			
		//}
		//echo "Количество дел с описями: $n $cr";
		
		
		
		//$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
		//echo json_encode($c);
	

	
?>