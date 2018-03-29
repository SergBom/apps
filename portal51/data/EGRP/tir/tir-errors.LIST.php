<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$id = trim ((!empty($_GET['id'])) ? $_GET['id'] : 0 ); // 
/*---------------------------------------------------------------------------*/

	$conn = ConnectLocalTIR(); // Присоска к базе

	// Генерим список Кадастровых районов
	//$sql = "SELECT ID,SHORT_NAME FROM rp_depts ORDER BY ID";
	$sql = "select ID,PICTURE,ERROR_TEXT1 from T\$STATUS_ERROR order by ID";
	$stid = oci_parse($conn, $sql);
	oci_execute($stid);
	
	//$data[] = array();
	while (($row = oci_fetch_array($stid))){	

		$data[] = //$row;
		
		array(
			'id'=>$row['ID'],
			'text'=>htmlspecialchars($row['PICTURE']).' '.$row['ERROR_TEXT1'],
//			'text'=>$row['ERROR_TEXT1'],
			'picture'=>htmlspecialchars($row['PICTURE'])
		);

		//echo htmlspecialchars($row['PICTURE'])."<br>";
	}

	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);


//@oci_free_statement($stid);
//@oci_close($conn);
?>