<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/***************************************************************
*/
//$otdel = trim ((!empty($_GET['otdel'])) ? $_GET['otdel'] : "" );
//$session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );

	$conn = ConnectLocalTIR(); // Присоска к базе

	// Генерим список Кадастровых районов
	//$sql = "SELECT ID,SHORT_NAME FROM rp_depts ORDER BY ID";
	$sql = "select * from T\$OTDELS_TMP order by ID";
	$stid = oci_parse($conn, $sql);
	oci_execute($stid);

	
	//oci_fetch_all($stid, $data);
	
		$data[] = array(
			'id'=>888,
			'name'=>"(0) Все отделы"
		);
		
		
	
	while (($row = oci_fetch_array($stid))){	

		$data[] = array(
			'id'=>$row['ID'],
			'name'=>"({$row['ID']}) {$row['NAME']}"
		);

	}

	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);


//@oci_free_statement($stid);
//@oci_close($conn);
?>