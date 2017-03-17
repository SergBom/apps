<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	
//echo "session=".$_REQUEST['session']."   otdel=".$_REQUEST['otdel']."   stype=".$_REQUEST['stype']."<br>";
	
if($_REQUEST['session'] AND $_REQUEST['otdel'] AND $_REQUEST['stype']){
	
	$session = isset($_REQUEST['session'])  ? $_REQUEST['session']  :  0;
	
//	if($session){

		$otdel = isset($_REQUEST['otdel']) ? $_REQUEST['otdel']  :  0;
		$stype = isset($_REQUEST['stype']) ? $_REQUEST['stype']  :  'R'; // R= ЕГРП или C= ГКН

		$Rotdel = ( $otdel ) ? "and DEPT_ID in (select ID FROM rp_depts where dept_id=$otdel)"  : "" ;
		$Rotdel = ( $otdel==12 ) ? "AND DEPT_ID='' OR DEPT_ID IS NULL " : $Rotdel;
		$Rotdel = ( $otdel==888 ) ? " " : $Rotdel;
		$SType =  ( $stype == 'C' ) ? "AND SYSTEM_TYPE='ГКН'" : "AND ( SYSTEM_TYPE IS NULL OR SYSTEM_TYPE='ЕГРП' )";
/*---------------------------------------------------------------------------*/

	$conn = ConnectLocalTIR(); // Присоска к базе

	// Генерим список Кадастровых районов
	//$sql = "SELECT ID,SHORT_NAME FROM rp_depts ORDER BY ID";
	
/*	$sql = "SELECT DISTINCT DESCRIPTION, DOC_GUID
                FROM V\$FLK o
                WHERE SESSION#=$session
					$SType
                    $Rotdel
                ORDER BY DESCRIPTION";
				*/
	$sql = "SELECT * FROM T\$ERROR_DESC";
//				echo $sql;

	$stid = oci_parse($conn, $sql);
	oci_execute($stid);

		$data[] = array(
			'id'=>0,
			'text'=>'Все ошибки'
		);

	while (($row = oci_fetch_array($stid))){	

		$data[] = array(
//			'id'=>"'".utf8_encode($row['DESCRIPTION'])."'",
			'id'=>$row['ID'],
			'text'=>$row['DESCRIPTION']
		);

	}

	$c = array('success'=>'true','data'=>$data);
	echo json_encode($c);


//	@oci_free_statement($stid);
//	@oci_close($conn);
}
?>