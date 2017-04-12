<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$data = array();
	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('portal');

/*---------------------------------------------------------------------------*/

	//$sql = "SELECT id,name,concat('{',cn,'} - ',name) as n FROM `prt#otdels` ORDER BY code, n";
	$sql = "select * from `prt#otdels` where org_id=1 order by name";

	$result = $db->query( $sql );

	while( ($row = $result->fetch()) != false ) {
			
			$data[] = $row;
			
	}

	echo json_encode(array('success'=>'true','data'=>$data));


?>