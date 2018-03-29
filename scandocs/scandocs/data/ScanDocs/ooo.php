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


    $db = ConnectPDO('Scan_docs');
	
	$st_l1 = $db->query( "select * from docs2 where tp=1" ); //PDO::FETCH_COLUMN);
	
	while ( $row_l1 = $st_l1->fetch() ){
		
		$sql = "INSERT INTO docs_l1 (name,cdate,cyear,path,opis,retro) VALUES (
		'{$row_l1['name']}','{$row_l1['cdate']}','{$row_l1['cyear']}','{$row_l1['path']}','{$row_l1['opis']}','{$row_l1['retro']}'
		)";
		echo $sql.$cr;
		$db->query( $sql );
		
		$id_l1 = $db->lastInsertId(); 
		
		$sql = "select * from docs2 where tp=2 and par_id={$row_l1['id']}";
		echo $sql.$cr;
		$st_l2 = $db->query( $sql );


		while ( $row_l2 = $st_l2->fetch() ){
		
			$sql = "INSERT INTO docs_l2 (id_l1,name,cdate,cyear) VALUES (
			'{$id_l1}','{$row_l2['name']}','{$row_l2['cdate']}','{$row_l2['cyear']}'
			)";
			echo "= ".$sql.$cr;
			$db->query( $sql );
		
			$id_l2 = $db->lastInsertId(); 
		
			
			
			$sql = "INSERT INTO docs_l3 (id_l2,name,cdate,cyear,cnt_size)
					SELECT $id_l2,name,cdate,cyear,cnt_size FROM docs2 WHERE (tp=3 or tp=4) and par_id={$row_l2['id']}
			";
			echo "== ".$sql.$cr;
			
			$db->query( $sql );

		}


		
		
		
	}
	
	
	
	
	//echo json_encode(array('success'=>'true','data'=>$data));
	
?>