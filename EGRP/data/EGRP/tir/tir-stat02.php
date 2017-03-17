<?php
/**************************************************************
 Статистика для налоговой
*/
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query2.php");
header('Content-type: text/html; charset=utf-8');

/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "" ); // По умолчанию Для налоговой, иначе для систем (R-ЕГРП) (C-ГКН)
/****************************************************************************/

$conn = ConnectLocalTIR(); // Присоска к базе

if($TIR_System==''){
	$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat1']);
} else {
	$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat1N']);
	$sql = str_replace(':ST',$TIR_System,$sql);
}

//echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);

$CalcNum = 0;
$data = array();
$C=0;
while( $row = oci_fetch_array($stid) ){
	$CalcNum += $row['NUMS'];
	array_push($data, array(
		'id'=>$row['ROWNUM'],
		'name'=>"{$row['DESCRIPTION']}",
		'tire'=>"-",
		'calc'=>$row['NUMS']
	));
	$C++;
}

	array_push($data, array(
		'id'=>$C+1,
		'name'=>"Всего:",
		'tire'=>"-",
		'calc'=>"<b>$CalcNum</b>"
	));

		echo json_encode(array(
				"success"=>"true",
				"data"=>$data
			));		
						
	
?>