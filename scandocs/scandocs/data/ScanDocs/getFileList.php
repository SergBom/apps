<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

$msg = "";

$id = $_POST['id'];
	
		

	$rt = $db->query( "select id,name from docs2 where par_id=$id order by id" );
	while( ($rowt = $rt->fetch()) != false ) {
			//$row['fname'] = $rp . $row['fname'];
		$msg .= "<u>".$rowt['name'].":</u><br>";
		$rf = $db->query( "select left(name, CHAR_LENGTH(name)-4) name  from docs2 where par_id=".$rowt['id']." and right(name,3)='pdf'" );
		
		while( ($row = $rf->fetch()) != false ) {
			$msg .= $row['name']."<br>";
		}
	}

	
	$c = array('success'=>true,'msg'=>$msg);
	echo json_encode($c);
	

?>