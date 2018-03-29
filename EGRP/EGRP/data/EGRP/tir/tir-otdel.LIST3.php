<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/***************************************************************
*/

	$conn = ConnectLocalTIR(); // Присоска к базе

	$sql = "SELECT * FROM V\$OTDELS";
	
	$stid = oci_parse($conn, $sql);
	oci_execute($stid);

	
	oci_fetch_all($stid, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	
	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);

?>