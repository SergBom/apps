<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_GET;
	$s = "";
	
	
	if( ! isset($adata->allow) || $adata->allow == 0 ){
		$s = " where id not in (select rec_id from `GZC#v#ParamsGroup`)";
	}
	$sql = "select id, concat('{',R1,'} = ',R2) R  from `GZC#Record` $s"; // where id not in (select rec_id from `GZC#v#ParamsGroup`)";
	// where g_id={$adata->g_id}
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}
		
?>