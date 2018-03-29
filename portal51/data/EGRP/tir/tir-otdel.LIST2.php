<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/***************************************************************
*/

	$conn = ConnectLocalTIR(); // Присоска к базе


	$sql = "SELECT * FROM (
				SELECT -888 AS ID, '(0) Все отделы' AS NAME FROM dual
				union
				select id, '('||ID||') '||NAME as NAME from T\$OTDELS_TMP order by ID
			)";
	
	$stid = oci_parse($conn, $sql);
	oci_execute($stid);

	
	oci_fetch_all($stid, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	
	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);

?>